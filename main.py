# NOTE  User avatars are stored in a public bucket, i tried for a solid day to try use signed-urls with base64 encoding but i had to luck
#       i assume using a public bucket is fine.


import uuid
from datetime import datetime

from flask import Flask, render_template, request, redirect, url_for, session, send_file
from google.auth.transport import requests
from google.cloud import datastore, storage
# end-imports

# Definitions
datastore_client = datastore.Client()
storage_client = storage.Client()
app = Flask(__name__)
app.secret_key = ####
# end-definitions

# Class definitions
class User:
    username = None
    password = None
    id       = None
    avatar   = None

class ForumPost:
    post_content  = None
    post_subject  = None
    post_user     = None
    post_image    = None
    post_date     = None
    post_time     = None
    post_key      = None
# end-class-definitions

# Function definitions
# Authentication related functions
def authentication_validate_user(userid, password):
    user = User()
    #Get users from google datastore
    query = datastore_client.query(kind="user")
    results = list(query.fetch())

    for x in results:
        if str(x['id']) == str(userid):
            if str(x['password']) == str(password):
                user.username = str(x['user_name'])
                user.password = str(x['password'])
                user.id       = str(x['id'])
                user.avatar   = str("https://storage.googleapis.com/cloudcomputing-avatars/" + user.id +".png")

    return user

def authentication_get_user(userid):
    user = User()

    #Get users from google datastore
    query = datastore_client.query(kind="user")
    results = list(query.fetch())

    for x in results:
        if str(x['id']) == str(userid):
            user.username = str(x['user_name'])
            user.password = str(x['password'])
            user.id       = str(x['id'])
            user.avatar   = str("https://storage.googleapis.com/cloudcomputing-avatars/" + user.id +".png") 

    return user
    
def authentication_userid_exist(userid):

    #Get users from google datastore
    query = datastore_client.query(kind="user")
    results = list(query.fetch())

    for x in results:
        if str(x['id']) == str(userid):
            return True

    return False

def authentication_username_exist(username):

    #Get users from google datastore
    query = datastore_client.query(kind="user")
    results = list(query.fetch())

    for x in results:
        if str(x['user_name']) == str(username):
            return True

    return False

def authentication_password_matches(password):
    #Get users from google datastore
    query = datastore_client.query(kind="user")
    results = list(query.fetch())

    for x in results:
        if str(x['id']) == str(session['id']):
            if str(x['password']) == str(password):
                return True

    return False

def authentication_change_password(userid, new_password):
    query = datastore_client.query(kind="user")
    query.add_filter('id', "=", userid)

    for item in query.fetch():
        entity = datastore_client.get(item.key)
        entity['password'] = new_password
        datastore_client.put(entity)


# Forum related functions
def forum_fetch_all():
    forum_posts = []

    #Get users from google datastore
    query = datastore_client.query(kind="forum-post")
    query.order = ["-post-date"]
    results = list(query.fetch())

    count = 0

    for x in results:
        if count == 10:
            return forum_posts
        post = ForumPost()
        post.post_content = str(x['post-content'])
        post.post_subject = str(x['post-subject'])
        post.post_user    = authentication_get_user(x['poster-userid'])
        post.post_date    = str(x['post-date'])
        post.post_time    = str(x['post-time'])
        if not x['post-image']:
            post.post_image = None
        else:
            post.post_image   = str(x['post-image'])
        forum_posts.append(post)
        count += 1

    return forum_posts

def forum_fetch_for_user(user_id):
    forum_posts = []

    # Fetch all posts from datastore where id == @param
    query = datastore_client.query(kind="forum-post")
    query.add_filter("poster-userid", "=", user_id)
    results = list(query.fetch())

    for x in results:
        post = ForumPost()
        post.post_subject = str(x['post-subject'])
        post.post_date    = str(x['post-date'])
        post.post_time    = str(x['post-time'])
        post.post_key     = str(x.key.id)
        forum_posts.append(post)

    return forum_posts

def forum_fetch_post_by_key(user_id, post_key):
    # Fetch post from datastore where key == @param
    
    post = ForumPost()

   # Fetch all posts from datastore where id == @param
    query = datastore_client.query(kind="forum-post")
    query.add_filter("poster-userid", "=", user_id)
    results = list(query.fetch())

    for x in results:
        if str(x.key.id) == post_key:
            post.post_subject    = str(x['post-subject'])
            post.post_content    = str(x['post-content'])
            post.post_image      = str(x['post-image'])
            post.post_key        = str(x.key.id)

    return post

def forum_edit_post_by_key(user_id, post_key, new_subject, new_content):
    query = datastore_client.query(kind="forum-post")
    query.add_filter("poster-userid", "=", user_id)

    for item in query.fetch():
        if str(item.key.id) == str(post_key):
            entity = datastore_client.get(item.key)
            entity['post-subject'] = new_subject
            entity['post-content'] = new_content
            datastore_client.put(entity)

# end-function-definitions



# Index route
@app.route('/', methods=['POST', 'GET'])
def index():
    if 'username' in session:
        return redirect(url_for('forum'))
    else:
        return redirect(url_for('login'))
# end-index-route



# Login route
@app.route('/login/', methods=['POST', 'GET'])
def login():
    error_message = None
    authenticated = False

    if 'username' in session:
        return redirect(url_for('error', message="You are already logged-in!", destination='forum'))

    if request.method == "POST":
        # Get form fields
        form_userid = request.form['id_field']
        form_password = request.form['password_field']

        if not form_userid:
            error_message = "Userid field is empty!"
            return render_template('login.php', error_message=error_message)

        if not form_password:
            error_message = "Password field is empty!"
            return render_template('login.php', error_message=error_message)
    
        #Get users from google datastore
        query = datastore_client.query(kind="user")
        results = list(query.fetch())

        for x in results:
            if str(x['id']) == (str(form_userid)):
                if str(x['password']) == str(form_password):
                    authenticated = True

        if authenticated:
            user = authentication_validate_user(form_userid, form_password)
            session['username'] = user.username
            session['id'] = user.id
            session['avatar'] = "https://storage.googleapis.com/cloudcomputing-avatars/" + user.id +".png"
            return redirect(url_for('forum'))
        else:
            error_message = "Username / Password invalid."
            return render_template('login.php', error_message=error_message)

    return render_template('login.php')
# end-login-route



# Logout route
@app.route('/logout/', methods=['POST', 'GET'])
def logout():
    if 'username' in session:
        session.pop('username', None)
        session.pop('id', None)
        session.pop('avatar', None)
        return redirect(url_for('login'))
    else:
        return redirect(url_for('error', message="You cannot logout if you weren't logged in!", destination='login'))
# end-logout-route



# Register route
@app.route('/register/', methods=['POST', 'GET'])
def register():
    if 'username' in session:
        return redirect(url_for('error', message="You are already logged-in!", destination='forum'))


    if request.method == "POST":
        # Get form fields
        form_userid   = request.form['id_field']
        form_username = request.form['username_field']
        form_password = request.form['password_field']

        uploaded_avatar = request.files['file']
        if uploaded_avatar.filename == '':
            error_message = "Avatar field is empty!"
            return render_template('register.php', error_message=error_message)


        if not form_userid:
            error_message = "Userid field is empty!"
            return render_template('register.php', error_message=error_message)

        if not form_username:
            error_message = "Username field is empty!"
            return render_template('register.php', error_message=error_message)

        if not form_password:
            error_message = "Password field is empty!"
            return render_template('register.php', error_message=error_message)

        if authentication_userid_exist(form_userid):
            error_message = "Userid already exists!"
            return render_template('register.php', error_message=error_message)

        if authentication_username_exist(form_username):
            error_message = "Username already exists!"
            return render_template('register.php', error_message=error_message)

        # Creating the new user
        task = datastore.Entity(datastore_client.key("user"))
        task.update(
            {
                "id" : form_userid,
                "password" : form_password,
                "user_name" : form_username,
            }
        )

        datastore_client.put(task)

        bucket = storage_client.get_bucket("cloudcomputing-avatars")
        blobname = str(form_userid) + '.png'
        blob = bucket.blob(blobname)
        blob.upload_from_string(uploaded_avatar.read(), content_type=uploaded_avatar.content_type)

        return redirect(url_for('success', message="Successfully created account!, please login with your new details.", destination='login'))

    return render_template('register.php')
# end-register-route



# Forum route
@app.route('/forum/', methods=['POST', 'GET'])
def forum():
    if 'username' in session:
        forum_posts = forum_fetch_all()

        if request.method == "POST":
            includes_image = False

            # Get form fields
            form_subject = request.form['form_subject']
            form_content = request.form['form_content']

            # Get uploaded file, if filename is null an image is not included
            uploaded_image = request.files['file']
            if uploaded_image.filename != '':
                includes_image = True


            if not form_subject:
                message = "Subject field is empty!"
                return render_template('forum.php', forum_posts=forum_posts, message=message)

            if not form_content:
                message = "Content field is empty!"
                return render_template('forum.php', forum_posts=forum_posts, message=message)
            
            now = datetime.now()
            date_str = now.strftime("%Y-%d-%b")
            time_str = now.strftime("%H:%M:%S")

            image_url = None
            image_uuid = str(uuid.uuid4())

            if includes_image:
                bucket = storage_client.get_bucket("cloudcomputing-forum-pictures")
                blobname = image_uuid + '.png'
                blob = bucket.blob(blobname)
                blob.upload_from_string(uploaded_image.read(), content_type=uploaded_image.content_type)
                image_url = blob.public_url

            # Creating the new post
            post = datastore.Entity(datastore_client.key("forum-post"))
            post.update(
                {
                    "poster-userid" : session['id'],
                    "post-content" : form_content,
                    "post-subject" : form_subject,
                    "post-date" : date_str,
                    "post-time" : time_str,
                    "post-image" : image_url,
                }
            )

            datastore_client.put(post)
            return redirect(url_for('forum'))


        return render_template('forum.php', forum_posts=forum_posts)
    else:
        return redirect(url_for('error', message="You are not logged in!", destination='login'))
# end-forum-route



# Profile route
@app.route('/profile/', methods=['POST', 'GET'])
def profile():
    if 'username' not in session:
        return redirect(url_for('error', message="You are not logged in!", destination='login'))

    my_posts = forum_fetch_for_user(session['id'])


    return render_template('profile.php', my_posts=my_posts)
# end-profile-route



# Password-change route
@app.route('/password_change/', methods=['POST', 'GET'])
def password_change():
    if request.method == "POST":
        # Get form fields
        form_oldpassword   = request.form['form_oldpassword']
        form_newpassword = request.form['form_newpassword']

        if not form_oldpassword:
            message = "Old password field is empty!"
            return render_template('profile.php', message=message)

        if not form_newpassword:
            message = "New password field is empty!"
            return render_template('profile.php', message=message) 

        if authentication_password_matches(form_oldpassword):
            authentication_change_password(session['id'], form_newpassword)
            return redirect(url_for('success', message="Successfully changed password.", destination='forum'))
        else:
            message = "Old password does not match!"
            return render_template('profile.php', message=message) 

    return redirect(url_for('profile'))
# end-password-change-route



# Post-edit route
@app.route('/post_edit/<post_key>', methods=['POST', 'GET'])
def post_edit(post_key):
    post = forum_fetch_post_by_key(session['id'], post_key)

    if request.method == "POST":
        # Get form fields
        form_subject = request.form['form_subject']
        form_message = request.form['form_message']

        if not form_subject:
            message = "Subject field is empty!"
            return render_template('edit_post.php', message=message)

        if not form_message:
            message = "Message field is empty!"
            return render_template('edit_post.php', message=message) 

        forum_edit_post_by_key(session['id'], post_key, form_subject, form_message)

        return redirect(url_for('forum'))


    return render_template('edit_post.php', post=post) 
# end-post-edit-route



# Error route
# Description: used to display error messages, two parameters, 
#              first the error message, and then the page you would like
#              the user to be redirected to. (10 second redirect)
@app.route('/error/<message>/<destination>', methods=['POST', 'GET'])
def error(message, destination):
    redirect_url = url_for(destination)
    return render_template('error.php', message=message, destination=redirect_url)
# end-error-route



# Success route
# Description: used to display success messages, two parameters, 
#              first the success message, and then the page you would like
#              the user to be redirected to. (10 second redirect)
@app.route('/success/<message>/<destination>', methods=['POST', 'GET'])
def success(message, destination):
    redirect_url = url_for(destination)
    return render_template('success.php', message=message, destination=redirect_url)
# end-success-route



if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8080, debug=True)
