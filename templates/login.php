{% extends 'base.php' %}


{% block head %}

<title>AS1-Login</title>

{% endblock %}



{% block body %}

<section class="contact-clean">
    <form action="/login/" method="POST">
        <h2 class="font-monospace text-center">Login</h2>
        <p name="error_message" style="color:red; text-align:center;">{{ error_message }}</p>
        <div class="mb-3"><input class="form-control" type="text"     name="id_field" id="id_field" placeholder="userid"></div>
                          <input class="form-control" type="password" name="password_field" id="password_field" placeholder="password">
        <div class="mb-3"></div>
        <div class="mb-3"></div>
        <div class="mb-3"><button class="btn btn-primary text-lowercase text-center" type="submit" id="submit_button" style="margin-top: 0px;padding: 10px 32px;width: 100%;height: auto;">Login</button></div>
        <a class="text-start" href="/register/">Don't have an account?</a>
    </form>
</section>

{% endblock %}