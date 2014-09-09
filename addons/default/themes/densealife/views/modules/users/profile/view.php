<!--<h2 class="page-title">{{ user:display_name user_id= _user:id }}</h2>


 Container for the user's profile 
<div id="user_profile_container">
	<?php echo gravatar($_user->email, 50);?>
	 Details about the user, such as role and when the user was registered 
	<div id="user_details">

		<table>
	
			{{# we use _user:id as that is the id passed to this view. Different than the logged in user's user:id #}}
			{{ user:profile_fields user_id= _user:id }}
				{{#   viewing own profile?    are they an admin?        ok it's a regular user, we'll show the non-sensitive items #}}
				{{ if user:id === _user:id or user:group === 'admin' or slug != 'email' and slug != 'first_name' and slug != 'last_name' and slug != 'username' and value }}
					<tr><td><strong>{{ name }}:</strong></td><td>{{ value }}</td></tr>
				{{ endif }}

			{{ /user:profile_fields }}

		</table>

	</div>
</div>-->

<div class="comman-box">
        	 <ul class="videos clearfix">
             	<li><img src="" /><span class="name">Ablums</span></li>
                <li><img src="" /><span class="name">Events</span></li>
	            <li><img src="" /><span class="name">Photos</span></li>
                <li><img src="" /><span class="name">Music</span></li>
            </ul>
        </div>
        <div class="comman-box clearfix">
                <ul class="search-box clearfix">
                <li><button>Following (179)</button></li>
                <li><button>Friends</button></li>
                <li><button>Message</button></li>
            </ul>
        </div>
    	<div class="comman-box clearfix">
			<div class="comman-heading">Photos</div>
            <ul class="search-box clearfix">
                <li class="right"><button>Add Album</button></li>
                <li class="right"><button class="btn-color">Add Photo/ Video</button></li>
            </ul>
            <ul class="videos">
            	<li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
	            <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
                <li><img src="" /><span class="name">Album 1 (32 Photos)</span></li>
            </ul>
        </div>