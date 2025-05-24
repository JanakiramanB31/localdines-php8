(function($) {
  function keyboard_price(cls, length) {
    $(cls).keyboard({
      layout: 'custom',
      customLayout: {
        'normal' : [
          '1 2 3 {b}',
          '4 5 6 {clear}',
          '7 8 9 {a}',
          '. 0 00 {c}'
        ]
      },
      maxLength : length,
      restrictInput : true,
    });
  }
  function keyboard_normal(cls) {
    console.log(cls);
    $(cls).keyboard({ layout: 'qwerty' });
  }
  function keyboard_email(cls) {
   
    $(cls).keyboard({

      display: {
        'bksp'   : '\u2190',
        'enter'  : 'Return',
        'normal' : 'ABC',
        'meta1'  : '.?123',
        'meta2'  : '#+=',
        'accept' : 'Accept'
      },

      layout: 'custom',

      customLayout: {

        'normal': [
          'q w e r t y u i o p {bksp}',
          'a s d f g h j k l {enter}',
          '{s} z x c v b n m @ . {s}',
          '{meta1} {space} _ - {accept}'
        ],
        'shift': [
          'Q W E R T Y U I O P {bksp}',
          'A S D F G H J K L {enter}',
          '{s} Z X C V B N M @ . {s}',
          '{meta1} {space} _ - {accept}'
        ],
        'meta1': [
          '1 2 3 4 5 6 7 8 9 0 {bksp}',
          '` | { } % ^ * / \' {enter}',
          '{meta2} $ & ~ # = + . {meta2}',
          '{normal} {space} ! ? {accept}'
        ],
        'meta2': [
          '[ ] { } \u2039 \u203a ^ * " , {bksp}',
          '\\ | / < > $ \u00a3 \u00a5 \u2022 {enter}',
          '{meta1} \u20ac & ~ # = + . {meta1}',
          '{normal} {space} ! ? {accept}'
        ]

      },

    });
  }
  function keyboard_numpad(cls, length) {
    $(cls).keyboard({
      layout: 'custom',
      customLayout: {
        'normal' : [
          '1 2 3',
          '4 5 6',
          '7 8 9',
          '{clear} 0 {b}',
          '{c} 00 {a}'
        ]
      },
      maxLength: length,
      restrictInput: true,
    });
  }
  function load_keyboard() {
    keyboard_email(".jsVK-email");
    keyboard_normal(".jsVK-normal");
    keyboard_price(".jsVK-price", 8);
    keyboard_numpad(".jsVK-numpad", 11);
    keyboard_numpad(".jsVK-numpad-table", 2);
  }
  $(document).ready(function() {
    load_keyboard();
  });
 
})(jQuery);