jQuery(document).ready(function($){

    var $grid = $('.grid').masonry({
      itemSelector: 'none', // select none at first
      columnWidth: '.grid__col-sizer',
      gutter: '.grid__gutter-sizer',
      percentPosition: true,
      // nicer reveal transition
      visibleStyle: { transform: 'translateY(0)', opacity: 1 },
      hiddenStyle: { transform: 'translateY(100px)', opacity: 0 },
    });

    // get Masonry instance
    var msnry = $grid.data('masonry');

    // initial items reveal
    $grid.imagesLoaded( function() {
      $grid.removeClass('are-images-unloaded');
      $grid.masonry( 'option', { itemSelector: '.grid__item' });
      var $items = $grid.find('.grid__item');
      $grid.masonry( 'appended', $items );
    });


    $grid.infinitescroll({

        // selector for the paged navigation (it will be hidden)
        navSelector  : ".navigation",
        // selector for the NEXT link (to page 2)
        nextSelector : ".navigation a",
        // selector for all items you'll retrieve
        itemSelector : ".grid__item",
        // finished message
        loading: {
                img: assetsURL + 'images/loader-square.gif',
                msgText: 'Loading new sets...',
                finishedMsg: 'No more pages to load.'
            }
        },

        // Trigger Masonry as a callback
        function( newElements ) {
            // hide new items while they are loading
            var $newElems = $( newElements ).css({ opacity: 0 });
            // ensure that images load before adding to masonry layout
            $newElems.imagesLoaded(function(){
                // show elems now they're ready
                $newElems.animate({ opacity: 1 });
                $grid.masonry( 'appended', $newElems, true );
            });

        }
    );
    
});
