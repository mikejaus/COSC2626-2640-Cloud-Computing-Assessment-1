{% extends 'base.php' %}


{% block head %}

<title>AS1-Profile</title>

{% endblock %}



{% block body %}

<section style="padding: 5%;">
    <div class="card" style="margin-bottom: 2%;">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active" href="#"><strong>User Information</strong></a></li>
            </ul>
        </div>
        <div class="card-body">
            <h4 class="card-title"><strong>User Information</strong></h4>
            <p class="card-text" style="margin-bottom: 5px;"><strong>ID: </strong>{{ session['id'] }}</p>
            <p class="card-text" style="margin-bottom: 5px;"><strong>Username: </strong>{{ session['username'] }}</p>
            <p class="card-text" style="margin-bottom: 5px;"><strong>Avatar: </strong></p><img src="{{ session['avatar'] }}" style="width: 120px;height: 120px;">
        </div>
    </div>
    <div class="card" style="margin-bottom: 2%;">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active" href="#"><strong>Edit Posts</strong></a></li>
            </ul>
        </div>
        <div class="card-body">
            <h4 class="card-title">Edit Posts</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Date Created</th>
                            <th>Time Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    {% for post in my_posts %}
                    <tbody>
                        <tr>
                            <td>{{post.post_subject}}</td>
                            <td>{{post.post_date}}</td>
                            <td>{{post.post_time}}</td>
                            <td><button class="btn btn-primary" onclick="window.location.href='/post_edit/{{post.post_key}}'" style="height: 100%;width: 100%;border-radius: 5px;background: rgb(34,38,40);">Edit Post</button></td>
                        </tr>
                        <tr></tr>
                    </tbody>
                    {% endfor %}
                </table>
            </div>
        </div>
    </div>
    <div class="card" style="margin-bottom: 5%;">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active" href="#"><strong>Change Password</strong></a></li>
            </ul>
        </div>
        <div class="card-body">
            <h4 class="card-title" style="margin-bottom: 15px;">Change Password</h4>
            <form action="/password_change/" method="POST">
                <p style="color:red;">{{ message }}</p>
                <input class="form-control" type="password" name="form_oldpassword" placeholder="old password" style="margin-bottom: 10px;">
                <input class="form-control" type="password" name="form_newpassword" placeholder="new password"  style="margin-bottom: 10px;">
                <button class="btn btn-primary" type="submit" style="width: 100%;border-radius: 5px;background: rgb(34,38,40);margin-bottom: 10px;">Change Password</button>
            </form>
        </div>
    </div>
</section>

{% endblock %}