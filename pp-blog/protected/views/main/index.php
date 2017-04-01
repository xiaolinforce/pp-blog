<!DOCTYPE html>
<html>

<head>

	<title>PP Blog</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<!-- Markdown Lib -->
	<script src="libs/markdown.js"></script>

	<style>
		#text_header {
			text-align: center;
			font-size: 50px;
			margin-top: 20px;
		}
		
		.div_post {
			padding: 5px 200px;
		}
		
		.text_post {
			background-color: #F2F2F2;
			padding: 30px;
			font-size: 30px;
			cursor: pointer;
		}
		
		.text_post:hover {
			background-color: #C5C5C5;
		}
		
		#text_no_post {
			padding: 30px;
			font-size: 30px;
			text-align: center;
			border: 1px solid #C5C5C5;
		}
		
		.text_composer {
			font-size: 16px;
			color: #898989;
		}
		
	</style>

</head>

<body>

	<p id="text_header">PP BLOG</p>
	
	<div style="text-align: center;">
		<button type="button" class="btn btn-default" onclick="onclickNewPost()">Create new post</button>
	</div>
	
	<div id="div_no_post" class="div_post" style="margin-top: 30px; display: none;">
		<p id="text_no_post">No post</p>
	</div>
	
	<div id="div_all_posts" style="margin-top: 30px;">
	
		<div class="div_post">
			<p class="text_post" onclick="onclickEachPost(0)">
				<span>Title</span><br>
				<span class="text_composer">by Piyawach</span>
			</p>
		</div>
	
	</div>
	
	<!-- Modal New Post -->
	<div id="modal_new_post" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header" style="background-color: #393939;">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<input id="in_post_title" type="text" placeHolder="Enter Title" style="font-size: 36px; width: 90%;">
				</div>
				<div class="modal-body">
					<div id="div_markdown_preview"></div>
					<textarea id="in_post_detail" rows="5" style="width: 100%; margin-top: 30px;" placeHolder="Content"></textarea>
					<input id="in_post_composer" type="text" placeHolder="Composer" style="font-size: 20px; width: 30%; margin-top: 30px;">
				</div>
				<div class="modal-footer" style="background-color: #393939;">
					<button type="button" class="btn btn-success" onclick="onclickSavePost()">Post</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
	
	<!-- Modal Post Detail -->
	<div id="modal_post_detail" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 id="text_post_title" class="modal-title" style="font-size: 30px;">Introduce myself</h4>
				</div>
				<div id="text_post_content" class="modal-body"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
	
	<script>
	
		$(document).ready(function(){
			onchangePostDetail();
			getAllPosts();
		});

		function getAllPosts() {
			$.ajax({
				url: "?r=Main/GetAllPost",
				data: {},
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					renderHtmlAllPosts( data );
				},
				error: function(){
					alert('ERROR');
				},
						        
			});
		}
		
		function renderHtmlAllPosts( aPosts ) {
			$('#div_all_posts').empty();
			for( i = 0; i < aPosts.length; i++ ) {
				
				sHtml = '<div class="div_post">' +
							'<p class="text_post" onclick="onclickEachPost('+aPosts[i].id+')">' +
								'<span>'+aPosts[i].title+'</span><br>' +
								'<span class="text_composer">by '+aPosts[i].composer+'</span>' +
							'</p>' +
						'</div>';
						
				$('#div_all_posts').append(sHtml);
			}
			if( aPosts.length == 0 )
				$('#div_no_post').show();
			else
				$('#div_no_post').hide();
		}
		
		function onclickEachPost( iPostId ) {
			$.ajax({
				url: "?r=Main/GetPostContent",
				data: {
					iId: iPostId
				},
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					$('#text_post_title').text( data.title );
					$('#text_post_content').html( data.content );
					$('#modal_post_detail').modal('show');
				},
				error: function(){
					alert('ERROR');
				},	        
			});
		}
		
		function onclickNewPost() {
			$('#modal_new_post').modal('show');
		}
		
		function onchangePostDetail() {
			$('#in_post_detail').on('input', function(){
				sHtml = markdown.toHTML( $('#in_post_detail').val() );
				$('#div_markdown_preview').html(sHtml);
			});
		}

		function onclickSavePost() {
			
			if( $('#in_post_title').val() == "" )
				alert('Please give some title');
			else if( $('#in_post_composer').val() == "" )
				alert('Please give some composer');
			else {

				$.ajax({
					url: "?r=Main/SavePost",
					data: {
						title: $('#in_post_title').val(),
						content: $('#div_markdown_preview').html(),
						composer: $('#in_post_composer').val()
					},
					type: 'POST',
					success: function(data){
						if( data == 1 ) {
							getAllPosts();
							$('#modal_new_post').modal('hide');
							$('#in_post_title').val('');
							$('#in_post_detail').val('');
							$('#in_post_composer').val('');
							$('#div_markdown_preview').html('');
						}
						else {
							alert('ERROR');
						}
					},
					error: function(){
						alert('ERROR');
					},
							        
				});
			}
		}
	
	</script>

</body>

</html>