{% extends 'base.php' %}


{% block head %}

<title>AS1-Forum</title>

{% endblock %}



{% block body %}

<section>
        <div class="container">
            <div class="d-lg-flex justify-content-lg-start align-items-lg-center" style="background: rgb(32,121,255);margin: 20px;border-radius: 5px;padding: 15px;">
                <div class="row" style="width: 100%;">
                    <div class="col d-xl-flex justify-content-xl-start align-items-xl-center" style="width: 0px;margin-left: 0px;text-align: left;"><img src="{{session['avatar']}}" style="width: 50px;height: 50px;margin-left: 1%;"></div>
                    <div class="col" style="width: 90%;margin: 0px;margin-left: -85%;">
                        <h1 style="font-size: 20px;color: rgb(255,255,255);margin-bottom: 0px;">Welcome <a href="/profile/" style="color:white; text-decoration:none;">{{ session['username'] }}</a></h1>
                        <p style="color: rgb(205,205,205);margin-bottom: 0px;">{{ session['id'] }}</p><a href="/logout/" style="color: rgb(255, 255, 255);"><strong>Logout</strong></a>
                    </div>
                </div>
            </div>
            <div style="background: rgb(255,255,255);margin: 20px;border-radius: 5px;padding: 20px;box-shadow: 0.2px 0.2px 19px rgba(33,37,41,0.08);height: 100%;">

                {% for post in forum_posts %}
                    <div class="card" style="width: 100%;margin-bottom: 8px;">
                        <div class="card-body" style="width: 100%;margin-bottom: 8px;">
                            <h4 class="card-title" style="margin-bottom: 8px;">{{post.post_subject}}</h4><img src="{{post.post_user.avatar}}" class="float-start d-lg-flex align-items-lg-center" style="width: 40px;height: 40px;margin-right: 10px;margin-bottom: 8px;">
                            <p class="float-none d-lg-flex align-items-lg-center card-text" style="margin-right:0px;margin-left:0px;margin-top: 15px;color: rgba(33,37,41,0.57);margin-bottom: 8px;"><em>{{post.post_user.username}}</em></p>
                            <p class="float-none d-lg-flex align-items-lg-center card-text" style="margin-right:0px;margin-left:0px;margin-top: 15px;margin-bottom: 8px;">{{post.post_content}}</p>
                            {% if post.post_image %}
                                <img src="{{post.post_image}}" style="margin-left: 4.5%; width: 50%;"></img>
                            {% endif %}
                            <p class="float-none d-lg-flex align-items-lg-center card-text" style="margin-right:0px;margin-left:0px;margin-top: 15px;color: rgba(33,37,41,0.57);"><em>{{post.post_date}} @ {{post.post_time}}</em></p>

                        </div>
                    </div>
                {% endfor %}

                <div class="card" style="width: 100%;margin-bottom: 8px; margin-top: 5%;">
                    <div class="card-body" style="width: 100%;margin-bottom: 8px;">
                        <h4 class="card-title" style="margin-bottom: 8px;">Post a message</h4>
                        <form method="post" enctype="multipart/form-data" style="margin-left: 0px;margin-right: 0px;text-align: left;">
                            <p style="color: var(--bs-red);">{{message}}</p>

                            <input class="form-control" type="text" name="form_subject" placeholder="subject" style="margin-bottom: 15px;">
                            <textarea class="form-control" name="form_content" placeholder="message content" rows="4" style="margin-bottom: 10px;"></textarea>

                            <p class="text-muted" style="margin-bottom: 10px;">Upload an image (optional)</p>
                            <input class="form-control" type="file" name="file" accept=".png,.jpg">
                            <div class="mb-3"></div>
                            <div class="mb-3"></div>
                            <div class="mb-3"></div>
                            <button class="btn btn-primary text-lowercase text-center" type="submit" style="margin-top: 0px;padding: 10px 32px;width: 100%;height: auto;padding-top: 5px;padding-bottom: 5px;">submit</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <script href="{{ url_for('static', filename='assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script href="{{ url_for('static', filename='assets/js/Material-Text-Input.js') }}"></script>

{% endblock %}