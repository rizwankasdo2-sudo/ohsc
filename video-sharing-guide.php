<?php
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=Video_Sharing_Guide.pdf");
@readfile('Video_Sharing_Guide.pdf');
