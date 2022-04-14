$(document).ready(function () {
    $(".btn quantity-left-minus").on("click", function () {
        var quantity = $("input[name='quantity']").val();
        if (quantity > 1) quantity--;
        else quantity = 1;
    });

    $(".btn quantity-right-plus").on("click", function () {
        var quantity = $("input[name='quantity']").val();
        quantity++;
    });

    $("#add-to-cart").on("click", function () {
        var url = $(this).data("url");
        var quantity = $("input[name='quantity']").val();
        url = url.replace("new_quantity", quantity);
        var notification = $("#cart").parent();
        var total = $("#cart_quantity").text();
        $.ajax({
            type: "GET",
            url,
            dataType: "json",
            data: {},
            success: function (response) {
                total = parseInt(total) + parseInt(quantity);
                $("#cart_quantity").text(total);
                notification.notify("Đã thêm vào giỏ hàng", {
                    position: "bottom center",
                    className: "success",
                });
            },
        });
    });

    $(".ti-search").on("click", function () {
        let url = $(this).data("url");
        $.ajax({
            type: "GET",
            url,
            dataType: "json",
            success: function (response) {
                var item = response.item;
                var sale_price = Intl.NumberFormat("vi-VN", {
                    style: "currency",
                    currency: "VND",
                }).format(item.price - (item.price * item.sale_off) / 100);
                var price = Intl.NumberFormat("vi-VN", {
                    style: "currency",
                    currency: "VND",
                }).format(item.price);
                $(".book-name").text(item.name);
                $(".book-price").text(sale_price);
                $(".book-price").append(" <del>" + price + "<del>");
                $(".book-description").html(
                    item.description.substring(0, 1000) + "..."
                );
                $("#quickview_img").attr("src", response.picture);
                $(".btn-view-book-detail").attr(
                    "data-url",
                    response.detailLink
                );
                $(".btn-add-to-cart").attr("data-url", response.cartLink);
            },
            error: function (data) {
                alert("fail");
            },
        });
    });

    $(".btn-view-book-detail").on("click", function () {
        var url = $(this).data("url");
        window.location.href = url;
    });

    $(".btn-view-book-detail").on("click", function () {
        var url = $(this).data("url");
        window.location.href = url;
    });

    $(document).on("click", "#remove-item", function (e) {
        var url = $(this).data("url");
        var btn = e.target;
        var total = $("#cart_quantity").text();
        var parent = $("#cart_quantity").parent();
        Swal.fire({
            title: "Bạn chắn chứ?",
            text: "Bạn có muốn bỏ sản phẩm khỏi giỏ hàng?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Đồng ý",
            cancelButtonText: "Hủy",
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    "Đã xóa",
                    "Sản phẩm đã được bỏ khỏi giỏ hàng",
                    "thành công"
                );
                $.ajax({
                    url,
                    dataType: "json",
                    success: function (response) {
                        $(btn).closest("tr").remove();
                        if (response.cart.quantity.length == 0) {
                            $("#cart-table").remove();
                            $(".cart-buttons").remove();

                            var h3 = $("#cart-container").append(
                                '<h3 style="text-align:center">Hiện tại không có sản phẩm nào trong giỏ hàng</h3>'
                            );
                        }
                        let quantity = 0;
                        let sum = 0;
                        for (var item in response.cart.quantity) {
                            quantity += parseInt(response.cart.quantity[item]);
                        }
                        for (var item in response.cart.price) {
                            sum += parseInt(response.cart.price[item]);
                        }
                        total = quantity;
                        $("#cart_sum").text(format_number(sum) + " đ");
                        $("#cart_quantity").text(total);
                        parent.notify("Đã cập nhật giỏ hàng", {
                            position: "bottom center",
                            className: "success",
                        });
                    },
                });
            }
        });
    });

    $(".ti-shopping-cart").on("click", function () {
        var url = $(this).parent().data("url");
        var quantity = 1;
        url = url.replace("new_quantity", quantity);

        var notification = $("#cart").parent();
        var total = $("#cart_quantity").text();
        $.ajax({
            type: "GET",
            url,
            dataType: "json",
            data: {},
            success: function (response) {
                total = parseInt(total) + parseInt(quantity);
                $("#cart_quantity").text(total);
                notification.notify("Đã thêm vào giỏ hàng", {
                    position: "bottom center",
                    className: "success",
                });
            },
        });
    });

    function getUrlParam(key) {
        let searchParams = new URLSearchParams(window.location.search);
        return searchParams.get(key);
    }
    //ajax for changing number of item in shopping cart
    $(document).on("change", "#ajax-number-change", function () {
        var url = $(this).data("url");
        var quantity = $(this).val();
        url = url.replace("new_quantity", quantity);
        var id = $(this).data("id");
        var price = $(this).data("price");
        var notification = $(this).parent();
        $("#total-item-price-" + id).text(format_number(quantity * price) + " đ");

        $.ajax({
            type: "GET",
            url,
            dataType: "json",
            data: {},
            success: function (response) {
                var item_price = $("#total-item-price").find(
                    "[data-id='" + id + "']"
                );
                let sum = 0;
                let total_quantity = 0;
                for (var item in response.cart.quantity) {
                    total_quantity += parseInt(response.cart.quantity[item]);
                }
                for (var item in response.cart.price) {
                    console.log(item);
                    sum += response.cart.price[item];
                }
                $("#cart_sum").text(format_number(sum) + " đ");
                $("#cart_quantity").text(total_quantity);
                notification.notify("Đã cập nhật giỏ hàng", {
                    position: "bottom center",
                    className: "success",
                });
            },
        });
    });

    function format_number(nStr) {
        nStr += "";
        x = nStr.split(".");
        x1 = x[0];
        x2 = x.length > 1 ? "." + x[1] : "";
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, "$1" + "," + "$2");
        }
        return x1 + x2;
    }

    $(".slide-5").on("setPosition", function () {
        $(this).find(".slick-slide").height("auto");
        var slickTrack = $(this).find(".slick-track");
        var slickTrackHeight = $(slickTrack).height();
        $(this)
            .find(".slick-slide")
            .css("height", slickTrackHeight + "px");
        $(this)
            .find(".slick-slide > div")
            .css("height", slickTrackHeight + "px");
        $(this)
            .find(".slick-slide .category-wrapper")
            .css("height", slickTrackHeight + "px");
    });

    $(".breadcrumb-section").css("margin-top", $(".my-header").height() + "px");
    $(".my-home-slider").css("margin-top", $(".my-header").height() + "px");

    $(window).resize(function () {
        let height = $(".my-header").height();
        $(".breadcrumb-section").css("margin-top", height + "px");
        $(".my-home-slider").css("margin-top", height + "px");
    });

    // show more show less
    if ($(".category-item").length > 10) {
        $(".category-item:gt(9)").hide();
        $("#btn-view-more").show();
    }

    $("#btn-view-more").on("click", function () {
        $(".category-item:gt(9)").toggle();
        $(this).text() === "Xem thêm"
            ? $(this).text("Thu gọn")
            : $(this).text("Xem thêm");
    });

    $("li.my-layout-view > img").click(function () {
        $("li.my-layout-view").removeClass("active");
        $(this).parent().addClass("active");
    });

    $('#sort-form select[name="sort"]').change(function () {
        // console.log(getUrlParam('filter_price'));
        if (getUrlParam("filter_price")) {
            $("#sort-form").append(
                '<input type="hidden" name="filter_price" value="' +
                    getUrlParam("filter_price") +
                    '">'
            );
        }

        if (getUrlParam("search")) {
            $("#sort-form").append(
                '<input type="hidden" name="search" value="' +
                    getUrlParam("search") +
                    '">'
            );
        }

        $("#sort-form").submit();
    });

    setTimeout(function () {
        $("#frontend-message").toggle("slow");
    }, 4000);
});

function activeMenu() {
    // let controller = getUrlParam('controller') == null ? 'index' : getUrlParam('controller');
    // let action = getUrlParam('action') == null ? 'index' : getUrlParam('action');
    let dataActive = controller + "-" + action;
    $(`a[data-active=${dataActive}]`).addClass("my-menu-link active");
}

function getUrlParam(key) {
    let searchParams = new URLSearchParams(window.location.search);
    return searchParams.get(key);
}
