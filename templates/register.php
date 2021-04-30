{% extends 'base.php' %}


{% block head %}

<title>AS1-Register</title>

{% endblock %}



{% block body %}

<section class="contact-clean">
        <form method="post" enctype="multipart/form-data">
            <h2 class="font-monospace text-center">Sign-up</h2>
            <p name="error_message" style="color:red; text-align:center; margin-top:-5%;">{{ error_message }}</p>
            <div class="mb-3">
                <input class="form-control" type="text" name="id_field" placeholder="user id"></div>
                <input class="form-control" type="text" name="username_field" placeholder="username">
            <div class="mb-3"></div>
                <input class="form-control" type="password" name="password_field" placeholder="password">
            <div class="mb-3"></div>
                <label class="form-label">user avatar</label>
                <input class="form-control" type="file" name="file" accept=".png,.jpg" style="margin-top: 0px;height: 38px;">
            <div class="mb-3"></div>
                <button class="btn btn-primary text-lowercase text-center" type="submit" style="margin-top: 0px;padding: 10px 32px;width: 100%;height: auto;">Complete sign-up</button>
        </form>
    </section>

{% endblock %}