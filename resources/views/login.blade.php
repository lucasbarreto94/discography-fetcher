<!-- View stored in resources/views/greeting.blade.php -->
<html>
  <style type="text/css">
  #login {
    display: none;
  }
  #loggedin {
    display: none;
  }
  </style>
    <body>
        <h1>First, log in to spotify</h1>
        <a href="{{ route('authentication') }}">Log in</a>
    </body>
</html>


