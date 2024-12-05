<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matuturo Admin</title>
    <link rel="stylesheet" href="xav.css">
  
</head>
<body>
  <div class="adminindex">
    <div class="contentcontainer">
      <div class="outline">   
        
          <table>
            <tr>
              <th class="sidebar">
                <a href="adminindex.html">Home</a>
                <a class="active" href="adminaccounts.html">Accounts</a>
                <a href="#contact">Placeholder</a>
                <a href="#about">PlaceHolder</a>
              
              </th>
              <th class="content">
                <h2>Accounts</h2>
                <table class="acctable">
                    <tr>
                        <th>Name</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td>Kathryn Lizard</td>
                        <td>******</td>
                        <td>hairkath@gmail</td>
                        <td><button onclick="alert('Reset Successful!');">reset</button></td>
                    </tr>
                    <tr>
                        <td>Aldous Richard</td>
                        <td>******</td>
                        <td>MLbestgame@gmail.com</td>
                        <td><button onclick="alert('Reset Successful!');">reset</button></td>
                    </tr>
                </table>
              </th>
            </tr>
            <tr>
              <th class="logoutbttn">
              <button style="width: 200px; margin-left: -125px;" class="bttn" onclick="window.location.href='adminlogin.html'">Logout</button>
            </th>
            </tr>
          </table>
    </div>
    </div>
  </div>
</body>
</html>
