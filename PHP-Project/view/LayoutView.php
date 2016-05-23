<?php
/**
  * Solution for assignment 2
  * @author Daniel Toll
  */

class LayoutView {
  public function render($isLoggedIn, LoginView $v, DateTimeView $dtv, RegistrationView $r, EditView $ev, ViewEntryView $vev) {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>The Initiative for Procrastination</title>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
  </head>
  <body>
    <h1 id="headline">The Initiative for Procrastination</h1>
    <?php
      if ($isLoggedIn) {
        echo "<h2>Logged in</h2>";
        if ($ev->clickedNewEntry())
          echo $ev->getStartLink();
        elseif($v->userWantsToView())
          echo $vev->getStartLink();
        else {
          echo $ev->getEntryLink();
        }
      }
        else {
        echo "<h2>Not logged in</h2>";
        if($r->clickedRegister())
          echo $r->getLoginLink();
        elseif(!$isLoggedIn)
          echo $r->getRegLink();
    }
  ?>
    <div class="container" >
      <?php
      if($r->clickedRegister() && $r->regSuccess() === false)
        echo $r->response();
      elseif($ev->clickedNewEntry() && $ev->saveSuccess() === false && $isLoggedIn) {
          echo $ev->response();
      }
      elseif($v->userWantsToView() && $v->clickedMenuItem() === true) {
          echo $vev->response();
      }


      else {
        if($isLoggedIn) {
          echo $v->response();
          echo $v->getMenu();
          echo $v->getLogoutButton();
        }

        echo $v->response();

      }

        $dtv->show();
      ?>
    </div>

    <div>
      <em>This site uses cookies to improve user experience. By continuing to browse the site you are agreeing to our use of cookies.</em>
    </div>
   </body>
</html>
<?php
  }
}