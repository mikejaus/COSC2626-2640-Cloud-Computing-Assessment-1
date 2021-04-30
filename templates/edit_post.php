{% extends 'base.php' %}


{% block head %}

<title>AS1-Profile</title>

{% endblock %}



{% block body %}

<section style="padding: 5%;">
    <div class="card" style="margin-bottom: 5%;">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item"><a class="nav-link active" href="#"><strong>Edit Post</strong></a></li>
                <li class="nav-item"></li>
                <li class="nav-item"></li>
            </ul>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <p name="error_message" style="color:red; text-align:center;">{{ message }}</p>
                <p style="margin-bottom: 5px;"><strong>Subject</strong></p>
                    <input class="form-control form-control-sm" type="text" value="{{ post.post_subject }}" id="form_subject" name="form_subject" placeholder="Enter a subject.." style="margin-bottom: 10px;">
                <p style="margin-bottom: 5px;"><strong>Message</strong></p>
                    <input class="form-control form-control-sm" type="text" value="{{ post.post_content }}" id="form_message" name="form_message" placeholder="Enter a message.." style="margin-bottom: 10px;/*rows: 5;*/">
                <p style="margin-bottom: 5px;"><strong>Post Image</strong></p>
                    <input class="form-control" type="file" accept=".png,.jpg" id="form_image" name="form_image" style="margin-bottom: 3%;">
                    
                <button class="btn btn-primary" type="submit" style="width: 100%;background: rgb(33,37,41);">Done</button>
            </form>
        </div>
    </div>
</section>

{% endblock %}