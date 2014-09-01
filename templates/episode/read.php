<video width="640" height="480" controls>
  	<source src="<?php echo URL::base('storage/film/'.$entry['movie']) ?>" type="video/mp4">
	<object data="movie.mp4" width="640" height="480">
	    <embed width="640" height="480" src="movie.swf">
  	</object>
</video>