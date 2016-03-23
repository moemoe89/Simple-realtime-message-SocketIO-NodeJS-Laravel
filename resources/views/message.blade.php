<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simple Realtime Message</title>
    {!! Html::style('css/bootstrap.min.css') !!}
  </head>
  <style>
  body { padding-top: 70px; }
  
  #load { height: 100%; width: 100%; }
  #load {
    position    : fixed;
    z-index     : 99999; /* or higher if necessary */
    top         : 0;
    left        : 0;
    overflow    : hidden;
    text-indent : 100%;
    font-size   : 0;
    opacity     : 0.6;
    background  : #E0E0E0  url({!! asset('images/load.gif') !!}) center no-repeat;
  }
  
  .RbtnMargin { margin-left: 5px; }
  
  </style>
<body>
  <div id="load">Please wait ...</div>
    <audio id="notif_audio"><source src="{!! asset('sounds/notify.ogg') !!}" type="audio/ogg"><source src="{!! asset('sounds/notify.mp3') !!}" type="audio/mpeg"><source src="{!! asset('sounds/notify.wav') !!}" type="audio/wav"></audio>

<nav class="navbar navbar-default navbar-fixed-top " role="navigation">
  <div class="container">
   <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="{{ URL::to('message') }}">Simple Realtime Message</a>
   </div>

   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav nav-pills pull-right" role="tablist">
      <li role="presentation"><a href="#">New messages <span class="badge" id="new_count_message">{{ $CountNewMessage }}</span></a></li>
    </ul>
   </div>

  </div>
</nav>
    
<div class="container">
 <div id="new-message-notif"></div>
  <div class="row">
     <div class="table-responsive">
        <table id="mytable" class="table table-bordred table-striped">
          <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Time</th>
            <th>Read</th>
          </thead>
       
          <tbody id="message-tbody">
      
       @if(count($ListMessage) > 0)
            
          @foreach($ListMessage as $row)
              
          <tr>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->subject }}</td>
            <td>{{ $row->created_at }}</td>
            <td><a style="cursor:pointer" data-toggle="modal" data-target=".bs-example-modal-sm" class="detail-message" id="{{ $row->id }}"><span class="glyphicon glyphicon-search"></span></a></td>
          </tr>

          @endforeach

       @else
              
              <tr id="no-message-notif">
                <td colspan="5" align="center"><div class="alert alert-danger" role="alert">
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                  <span class="sr-only"></span> No message</div>
                </td>
              </tr>
              
    @endif
        
           </tbody>
        </table>

    </div>
 </div>
</div>

<hr>
<footer class="text-center">Simple Realtime Message &copy 2015</footer>
<hr>

{!! Html::script('js/jquery-1.11.2.min.js') !!}
{!! Html::script('js/bootstrap.min.js') !!}
{!! Html::script('node_modules/socket.io-client/socket.io.js') !!}
	<script>
  $(document).ready(function(){

		$("#load").hide();

     $(document).on("click",".detail-message",function() {
      
      $( "#load" ).show();

       var dataString = { 
              id : $(this).attr('id'),
              _token : '{{ csrf_token() }}'
            };

        $.ajax({
            type: "POST",
            url: "{{ URL::to('message') }}",
            data: dataString,
            dataType: "json",
            cache : false,
            success: function(data){

              $( "#load" ).hide();
             
              if(data){

                $("#show_name").html(data.name);
                $("#show_email").html(data.email);
                $("#show_subject").html(data.subject);
                $("#show_message").html(data.message);

                var socket = io.connect( 'http://'+window.location.hostname+':3000' );
                
                socket.emit('update_count_message', { 
                  update_count_message: data.update_count_message
                });

              } 
          
            } ,error: function(xhr, status, error) {
              alert(error);
            },

        });

    });

  });

  var socket = io.connect( 'http://'+window.location.hostname+':3000' );

  socket.on( 'new_count_message', function( data ) {
  
      $( "#new_count_message" ).html( data.new_count_message );
      $('#notif_audio')[0].play();

  });

  socket.on( 'update_count_message', function( data ) {

      $( "#new_count_message" ).html( data.update_count_message );
    
  });

  socket.on( 'new_message', function( data ) {
  
      $( "#message-tbody" ).prepend('<tr><td>'+data.name+'</td><td>'+data.email+'</td><td>'+data.subject+'</td><td>'+data.created_at+'</td><td><a style="cursor:pointer" data-toggle="modal" data-target=".bs-example-modal-sm" class="detail-message" id="'+data.id+'"><span class="glyphicon glyphicon-search"></span></a></td></tr>');
      $( "#no-message-notif" ).html('');
      $( "#new-message-notif" ).html('<div class="alert alert-success" role="alert"> <i class="fa fa-check"></i><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>New message ...</div>');
  });

</script>
  </body>
</html>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">✕</button>
             <h4>Detail Message</h4>
      </div>
                  
      <div class="modal-body" style="text-align:center;">
        <div class="row-fluid">
          <div class="span10 offset1">
            <div id="modalTab">
              <div class="tab-content">
                <div class="tab-pane active" id="about">

                  <center>
                    <p class="text-left">
                      <b>Name</b> : <span id="show_name"></span><br />
                      <b>Email</b> : <span id="show_email"></span><br />
                      <b>Subject</b> : <span id="show_subject"></span><br />
                      <b>Message</b> : <span id="show_message"></span><br />
                    </p>
                    <br>
                  </center>
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
