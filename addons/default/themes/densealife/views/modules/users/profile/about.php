<div class="comman-box clearfix">
    <div class="comman-heading">About</div>
    <div class="clear"></div>
    {{user:profile user_id=_user:id}}
    <table style="text-align: left;">
       {{if bio!=''}}
       <tr>
           <th>About</th>
       </tr>
       <tr>
           <td>{{bio}}</td>
       </tr>
        {{endif}}
        
        <tr>
           <th>Username</th>
       </tr>
       <tr>
           <td>{{username}}</td>
       </tr>
       <tr>
           <th>First Name</th>
       </tr>
       <tr>
           <td>{{first_name}}</td>
       </tr>
       
       <tr>
           <th>Last Name</th>
       </tr>
       <tr>
           <td>{{last_name}}</td>
       </tr>
       
       <tr>
           <th>DOB</th>
       </tr>
       <tr>
           <td>{{ helper:date format="F,dS Y" timestamp=dob }}</td>
       </tr>
       
       <tr>
           <th>Email</th>
       </tr>
       <tr>
           <td>{{email}}</td>
       </tr>
       
       <tr>
           <th>Last Login</th>
       </tr>
       <tr>
           <td>{{ helper:date format="F,dS Y" timestamp=last_login }}</td>
       </tr>
    </table>
    {{/user:profile}}

</div>