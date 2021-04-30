<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url_for('static', filename='assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url_for('static', filename='assets/fonts/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ url_for('static', filename='assets/css/Contact-Form-Clean.css') }}">
    <link rel="stylesheet" href="{{ url_for('static', filename='assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ url_for('static', filename='style.css') }}">
    {% block head %} {% endblock %}
</head>

<body>
    <nav class="navbar navbar-dark navbar-expand-md bg-dark" style="font-size: 12;color: rgb(25,140,255);">
        <div class="container-fluid"><i class="fa fa-signal" style="margin-right: 10px;"></i><a
                class="navbar-brand font-monospace" href="/forum/" style="font-size: 16px;">AS1-CloudComputing</a><button
                data-bs-toggle="collapse" class="navbar-toggler" data-bs-target="#navcol-1"><span
                    class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse font-monospace d-xl-flex justify-content-xl-end" id="navcol-1"
                style="font-size: 14px;">
                <ul class="navbar-nav">
                    {% if session['id'] %}
                        <li class="nav-item"><a class="nav-link text-start" href="/forum/">Forum</a></li>
                        <li class="nav-item"><a class="nav-link text-start" href="/profile/">Profile</a></li>
                    {% endif %}
                    
                    {% if not session['id'] %}
                        <li class="nav-item"><a class="nav-link text-start active" href="/login/">Login/Sign-up</a></li>
                    {% endif %}
                    
                    {% if session['id'] %}
                        <li class="nav-item" id="sign-out"><a class="nav-link text-start" style="color:red;" href="/logout/">Logout</a></li>
                    {% endif %}
                </ul>
            </div>
        </div>
    </nav>
    {% block body %} {% endblock %}
</body>
</html>