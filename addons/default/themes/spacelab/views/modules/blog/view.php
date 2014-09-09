<style>
#create-comment {
  	background-color: #717171;
  	padding: 10px;
}

#create-comment input, #create-comment textarea { 
	padding: 9px;
	border: solid 1px #E5E5E5;
	outline: 0;
	font: normal 13px/100% Verdana, Tahoma, sans-serif;
	width: 200px;
	background: #FFFFFF url('bg_form.png') left top repeat-x;
	background: -webkit-gradient(linear, left top, left 25, from(#FFFFFF), color-stop(4%, #EEEEEE), to(#FFFFFF));
	background: -moz-linear-gradient(top, #FFFFFF, #EEEEEE 1px, #FFFFFF 25px);
	box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-moz-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	-webkit-box-shadow: rgba(0,0,0, 0.1) 0px 0px 8px;
	}

#create-comment textarea { 
	width: 400px;
	max-width: 400px;
	height: 150px;
	line-height: 150%;
	}

#create-comment input:hover, #create-comment textarea:hover,
#create-comment input:focus, #create-comment textarea:focus { 
	border-color: #C9C9C9; 
	-webkit-box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 8px;
	}

#create-comment label { 
	margin-left: 10px; 
	color: #000; 
	}
</style>

<section class="clearfix">
	<!-- Post heading -->
	<div class="box">
		<h1><?php echo $post->title; ?></h1>
		<?php if (isset($post->display_name)): ?>
		<?php echo anchor('user/' . $post->author_id, $post->display_name); ?>
		<?php endif; ?>
		&nbsp;- <?php echo format_date($post->created_on); ?>
		<p><?php echo $post->body; ?></p>
	</div>
</section>

<section class="clearfix">
	<div class="box">
		<?php if ($post->comments_enabled): ?>
			<?php echo display_comments($post->id); ?>
		<?php endif; ?>
	</div>
</section>