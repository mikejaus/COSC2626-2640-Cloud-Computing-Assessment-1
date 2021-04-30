{% extends 'base.php' %}


{% block head %}

<title>AS1-Error</title>

{% endblock %}



{% block body %}

<script>
    setTimeout(function() { window.location.href = '{{ destination }}'; }, 4000); 
</script>

<section>
    <div class="container">
        <div style="background: rgb(255,255,255);margin: 20px;border-radius: 5px;padding: 20px;box-shadow: 0.2px 0.2px 19px rgba(33,37,41,0.08);height: 100%;margin-right: 150px;margin-left: 150px;padding-top: 50px;padding-bottom: 50px;margin-top: 100px;"><i class="fa fa-warning d-xl-flex justify-content-xl-center" style="font-size: 66px;padding-bottom: 10px;"></i>
            <p style="text-align: center;">{{ message }}</p>
            <p style="text-align: center;color: rgba(33,37,41,0.58);"><em>Redirecting..</em></p>
            <div class="d-xl-flex justify-content-xl-center align-items-xl-center"><span role="status" class="spinner-border spinner-border-sm text-center d-xl-flex" style="text-align: center;"></span></div>
        </div>
    </div>
</section>

{% endblock %}