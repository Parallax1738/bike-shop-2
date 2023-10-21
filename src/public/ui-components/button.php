<?php
  function button($params) {
    $text = $params['text'] ?? null;
    $iconSVG = $params['iconSVG'] ?? null;
    $targetPage = $params['targetPage'] ?? null;
    $isLoggedIn = $params['isLoggedIn'] ?? false;
    $type = $params['type'] ?? 'link';
    $action = $params['action'] ?? null;
    
?>
<div>
    <?php if ($type === 'submit'): ?>
    <button type="submit"
        class="w-full transform transition-all duration-100 bg-orange-500 hover:bg-orange-600 hover:shadow-lg hover:scale-105 text-white font-bold py-2 px-4 rounded flex items-center justify-center"
        name="<?php echo $action; ?>">
        <?php if ($iconSVG): ?>
        <div class="h-6 w-6 mr-2 flex items-center justify-center"><?php echo $iconSVG; ?></div>
        <?php endif; ?>
        <span class="flex items-center"><?php echo $isLoggedIn ? 'Account' : $text; ?></span>
    </button>
    <?php else: ?>
    <a href="<?php echo $isLoggedIn ? '/auth/edit-account' : $targetPage; ?>"
        class="transform transition-all duration-100 bg-orange-500 hover:bg-orange-600 hover:shadow-lg hover:scale-105 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
        <?php if ($iconSVG): ?>
        <div class="h-6 w-6 mr-2 flex items-center justify-center"><?php echo $iconSVG; ?></div>
        <?php endif; ?>
        <span class="flex items-center"><?php echo $isLoggedIn ? 'Account' : $text; ?></span>
    </a>
    <?php endif; ?>
</div>
<?php
  }
?>

<?php
/*
  HOW TO USE:

  First, include this function in your PHP by using: include('button.php');

  1. Button with Text Only
  ------------------------
  Example Usage:

  button(['text' => 'Click Me', 'targetPage' => 'some-page.php']);

  This will generate a button with the text "Click Me" that redirects to "some-page.php".

  2. Button with Text and Icon
  ----------------------------
  Example Usage:

  $iconSVG = '<svg>...</svg>';
  button(['text' => 'Click Me', 'iconSVG' => $iconSVG, 'targetPage' => 'some-page.php']);

  This will generate a button with the text "Click Me" and an SVG icon that redirects to "some-page.php".

  3. Button with Icon Only
  ------------------------
  Example Usage:

  $iconSVG = '<svg>...</svg>';
  button(['iconSVG' => $iconSVG, 'targetPage' => 'some-page.php']);

  This will generate a button with only an SVG icon that redirects to "some-page.php".

  4. Button for account logins
  -----------------------------
  Example Usage:

  button(['text' => 'Click Me', 'isLoggedIn' => true]);

  OR

  button(['text' => 'Click Me', 'isLoggedIn' => false]);
*/
?>