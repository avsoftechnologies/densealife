    <link href="<?php echo base_url('assets/jdrag/css/bootstrap.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/jdrag/assets/css/demo.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/jdrag/assets/css/bootstrap-responsive.min.css');?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url('assets/jdrag/js/jquery.imagedrag.min.js');?>"></script>
    <script type="text/javascript">
      $(function(){

        $('.cp-wrap').imagedrag({
          input: "#output",
          position: "middle",
          attribute: "html"
        });

      });
    </script>
    <style type="text/css">
      .cp-wrap {
        width: 938px;
        height: 280px;
        overflow: hidden;
        margin: auto;
        cursor: -webkit-grab;

      }
    </style>
   

 <div id="output"></div>

      <div class="jumbotron">
        <div class="cp-wrap">
          <img src="http://densealife.com/files/large/41c16346f0fc1f7a25ae71e0161e9a6e.jpg" alt="JQ Image Drag" style="width:1024px; height:768px;"/>
        </div>
      </div>