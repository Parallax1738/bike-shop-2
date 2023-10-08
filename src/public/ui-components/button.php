<?php
  function button($params) {
    $text = $params['text'] ?? null;
    $iconSVG = $params['iconSVG'] ?? null;
    $targetPage = $params['targetPage'] ?? null;
    $isLoggedIn = $params['isLoggedIn'] ?? false;
?>
<div>
    <a href="<?php echo $isLoggedIn ? 'account.php' : $targetPage; ?>"
        class="bg-orange-500 text-white font-bold py-2 px-4 rounded flex items-center justify-center">
        <?php if ($iconSVG): ?>
        <div class="h-6 w-6 mr-2 flex items-center justify-center"><?php echo $iconSVG; ?></div>
        <?php endif; ?>
        <span class="flex items-center"><?php echo $isLoggedIn ? 'Account' : $text; ?></span>
    </a>
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