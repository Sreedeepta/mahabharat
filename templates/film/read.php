<video width="320" height="240" controls autoplay>
  <source src="<?php echo URL::base('storage/film/'.$entry['movie']) ?>" type="video/mp4">
  <object data="movie.mp4" width="320" height="240">
    <embed width="320" height="240" src="movie.swf">
  </object>
</video>