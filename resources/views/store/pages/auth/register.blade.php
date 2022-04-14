<!DOCTYPE html>
<html lang="en">

@extends('store.main')

@section('content')
    <section class="register-page section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Đăng ký tài khoản</h3>
                    <div class="theme-card">
                        @include('store.blocks.notify')
                        <form action="{{ route('auth/postRegister') }}" method="post" id="admin-form"
                            class="theme-form">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="name" class="required">Tên tài khoản</label>
                                    <input type="text" id="form[name]" name="form[name]" value="" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="required">Email</label>
                                    <input type="email" id="form[email]" name="form[email]" value="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="required">Mật khẩu</label>
                                    <input type="password" id="form[password]" name="form[password]" value=""
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirm_password" class="required">Xác nhận mật khẩu</label>
                                    <input type="password" id="form[confirm_password]" name="form[confirm_password]"
                                        value="" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" id="form[_token]" name="form[_token]" value="{{ @csrf_token() }}">
                            <button type="submit" id="submit" name="submit" value="Tạo tài khoản" class="btn btn-solid">Tạo
                                tài khoản</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

</html>
