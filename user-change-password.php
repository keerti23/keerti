

<!DOCTYPE html>
<html class="no-js" dir="ltr" lang="en">
   <?php include 'includes/head.php'; ?>
   <body>
      <!-- menu -->
      <header id="menu-header">
         <?php include 'includes/header.php'; ?>
      </header>
      
      <section class="after-menu">
         <div class="grd-overlay">
         </div>
      </section>
      
      <section class="lift-up">
         <div class="row">
            <div class="large-10 column large-offset-1">
               <div class="white-box min-h">
                  <!-- profile details -->
                  <header class="pd-20 clearfix" id="user-details">
                     <div class="left">
                        <ul class="list-inline text-center-mobile">
                           <li><img class="user-avtar-md" src="includes/img/user.jpg"> </li>
                           <li>
                              <ul class="list-inline">
                                 <li style="list-style: none; display: inline">
                                    <h3><strong>Hemant Sen</strong> </h3>
                                 </li>
                                 <li>
                                    <a href=""><i class="fa fa-edit"></i> Edit</a>
                                 </li>
                              </ul>
                              <ul class="list-inline text-dull text-left">
                                 <li>Join On: <time>3 Jun, 2015</time></li>
                                 <li>Email: <span>hemant@gmail.com</span></li>
                                 <li>
                                    Status: <span class="text-dull">
                                    <i class="fa fa-exclamation-circle fa-lg text-red"></i>
                                    Incomplete Profile
                                    </span>
                                 </li>
                              </ul>
                           </li>
                        </ul>
                     </div>
                  </header>
                  
                  <!-- user-statistics -->
                  <section class="user-statistics">
                     <div class="row collapse">
                        <div class="large-3 column">
                           <div class="statistics">
                              <strong>$ 2,257.12</strong><br>
                              <span>MSRP of Products</span>
                           </div>
                        </div>
                        <div class="large-9 column bg-statistics">
                           <div class="row">
                              <div class="medium-3 small-6 column">
                                 <div class="statistics">
                                    <strong>15</strong><br>
                                    <span>Products Requested</span>
                                 </div>
                              </div>
                              <div class="medium-3 small-6 column">
                                 <div class="statistics">
                                    <strong>11</strong><br>
                                    <span>Reviews Completed</span>
                                 </div>
                              </div>
                              <div class="medium-3 small-6 column">
                                 <div class="statistics">
                                    <strong>2</strong><br>
                                    <span>Reviews Pending</span>
                                 </div>
                              </div>
                              <div class="medium-3 small-6 column">
                                 <div class="statistics">
                                    <strong>1</strong><br>
                                    <span>Reviews Overdue</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  
                  <!-- user account navigation -->
                  <nav class="yo-nav" id="user-nav">
                     <ul class="yo-mobile">
                        <li class="yo-title"> Option </li>
                        <li class="yo-collapse-icon"><i class="fa fa-bars"></i></li>
                     </ul>
                     <div class="yo-link-wrapper">
                        <ul class="yo-left">
                           <li><a  href="user-account.php">Current</a></li>
                           <li><a href="user-history.php">History</a></li>
                        </ul>
                        <ul class="yo-right">
                           <li class="has-child">
                              <a rel="yo">My Account</a>
                              <ul class="child-menu">
                                 <li><a href="user-edit-profile.php">Edit Profile</a></li>
                                 <li><a href="user-change-password.php" class="active">Change Password</a></li>
                              </ul>
                           </li>
                        </ul>
                     </div>
                  </nav>
                  
                  <!-- user change password section -->
                  <section class="pd-20">
                     <h3 class="line-left">Change Password</h3>
                     <hr>
                     <form action="" id="" method="" name="">
                        <div class="row">
                           <div class="medium-6 columns">
                              <label> Current Password
                              <input type="password"  name="" id="">
                              </label>
                              <label> New Password
                              <input type="password"  name="" id="">
                              </label>
                              <label> Confirm New Password
                              <input type="password"  name="" id="">
                              </label>
                              <div class="">
                                 <input class="button btn-outline btn-blue" name="submit" onclick="" type="submit" value="Change Password">
                              </div>
                           </div>
                        </div>
                     </form>
                  </section>
               </div>
            </div>
         </div>
      </section>
      
      <!-- footer -->
      <footer class="tmr-40">
         <?php include 'includes/footer.php'; ?>
      </footer>
   </body>
</html>

