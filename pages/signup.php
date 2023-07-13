<div class="container px-5 my-5">
    <div class="text-center mb-5">
    <h1 class="display-5 fw-bolder mb-0"><span class="text-gradient d-inline">Register</span></h1>
</div>
    <form action="<?=root('do/signup/'); ?>" method="post" class="form-floating">
        <!-- Email address input-->
        <div class="form-floating mb-3">
          <input class="form-control" id="cusername" type="email" name="cusername" placeholder="name@example.com"  required="required" />
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="password" name="cpassword" placeholder="Password"  required="required" />
          <label for="floatingPassword">Password</label>
        </div>
        <br/>
        <div class="d-grid">
            <button class="btn btn-primary btn-lg" id="submitButton" type="submit" name="submit">Submit</button>
        </div>
        <br/>
    </form>
</div>