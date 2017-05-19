<form action="/users" method="POST">
  {!! csrf_field() !!}
  Name: <input type="text" name="name" value=""><br><br>
  Email: <input type="email" name="email" value=""><br><br>
  Password: <input type="password" name="password" value=""><br><br>
  <input type="submit" value="Create">
</form>
