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
                           <li><img class="user-avtar-md" src="includes/img/user.jpg">
                           </li>
                           <li>
                              <ul class="list-inline">
                                 <li style="list-style: none; display: inline">
                                    <h3><strong>Hemant Sen</strong>
                                    </h3>
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
                           <li><a class="active" href="user-history.php">History</a></li>
                        </ul>
                        <ul class="yo-right">
                           <li class="has-child">
                              <a rel="yo">My Account</a>
                              <ul class="child-menu">
                                 <li><a href="user-edit-profile.php">Edit Profile</a></li>
                                 <li><a href="user-change-password.php">Change Password</a></li>
                              </ul>
                           </li>
                        </ul>
                     </div>
                  </nav>
                  <!-- user reviews history section -->
                  <section class="" >
                     <div class="pd-20 bg-dull">
                        <form class="history-search">
                           <div class="row">
                              <div class="medium-6 columns">
                                 <div class="row collapse tpd-20">
                                    <div class="small-9 columns">
                                       <input type="text" placeholder="Search by product name" class="small">
                                    </div>
                                    <div class="small-3 columns">
                                       <a class="button postfix btn-blue small" href="#"><i class="fa  fa-search"></i></a>
                                    </div>
                                 </div>
                              </div>
                              <div class="medium-3 columns">
                                 <label>
                                    Sort By
                                    <select>
                                       <option value="husker">Recent</option>
                                       <option value="starbuck">Due Date</option>
                                       <option value="hotdog">Other</option>
                                    </select>
                                 </label>
                              </div>
                              <div class="medium-3 columns">
                                 <div class="tpd-20">
                                    <button id="filter-btn" type="button" class="button small btn-outline btn-blue expand">
                                    Filter
                                    </button>

                                    
                                 </div>
                              </div>
                           </div>
                           <div id="user-src-filter">
                              <hr>
                              <div class="row">
                                 <div class="medium-4 column">
                                    <h4 class="dmr-20">Review Status</h4>
                                    <div>
                                       <input id="checkbox1" type="checkbox">
                                       <label for="checkbox1">
                                       Amazon Review Pending
                                       </label>
                                    </div>
                                    <div>
                                       <input id="checkbox1" type="checkbox">
                                       <label for="checkbox1">
                                       Amazon Review Done
                                       </label>
                                    </div>
                                    <div>
                                       <input id="checkbox1" type="checkbox">
                                       <label for="checkbox1">
                                       On Site Review Pending
                                       </label>
                                    </div>
                                    <div>
                                       <input id="checkbox1" type="checkbox">
                                       <label for="checkbox1">
                                       On Site Review Done
                                       </label>
                                    </div>
                                 </div>
                                 <div class="medium-8 column">
                                    <h4>Recieve Date</h4>
                                    <div class="row">
                                       <div class="medium-6 column">
                                          <label>From
                                          <input type="text" class="span2 small" value="" id="dp1">
                                          </label>
                                       </div>
                                       <div class="medium-6 column">
                                          <label>To
                                          <input type="text" class="span2 small" value="" id="dp2">
                                          </label>
                                       </div>
                                    </div>
                                    <h4>Due Date</h4>
                                    <div class="row">
                                       <div class="medium-6 column">
                                          <label>From
                                          <input type="text" class="span2 small" value="" id="dp3">
                                          </label>
                                       </div>
                                       <div class="medium-6 column">
                                          <label>To
                                          <input type="text" class="span2 small" value="" id="dp4">
                                          </label>
                                       </div>
                                    </div>
                                    <script type="text/javascript">
                                       $(document).ready(function(){
                                           $('#dp1, #dp2, #dp3, #dp4').fdatepicker({
                                             format: 'mm-dd-yyyy',
                                             disableDblClickSelection: true
                                           });
                                       }); 
                                    </script>
                                       
                                       
                                       
                                       
                                       
                                       
                                 </div>
                              </div>
                           </div>
                        </form>
                     </div>
                     <table class="rnr-table responsive">
                        <thead>
                           <tr>
                              <th width="110px">Recieve Date</th>
                              <th>Product</th>
                              <th width="110px">Due Date</th>
                              <th width="150px">Review On Amazon</th>
                              <th width="150px">Review On Site</th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td><time>3 Jun, 2015</time></td>
                              <td>
                                 <div class="row collapse">
                                    <div class="small-2 column text-center">
                                       <img src="http://ecx.images-amazon.com/images/I/51SW8VHeQjL._SX323_BO1,204,203,200_.jpg" class="table-img">
                                    </div>
                                    <div class="small-10 column">
                                       <h4><a href="history-review-details.php">This is longer content Donec id elit non mi porta gravida at eget metus.</a></h4>
                                       <small>Coupon Code: 345580238530</small>
                                    </div>
                                 </div>
                              </td>
                              <td><time>8 Jun, 2015</time></td>
                              <td><span class="text-red">Pending</span></td>
                              <td><span class="text-green">Done</span></td>
                           </tr>
                           <tr>
                              <td><time>3 Jun, 2015</time></td>
                              <td>
                                 <div class="row collapse">
                                    <div class="small-2 column text-center">
                                       <img src="http://ecx.images-amazon.com/images/I/51SW8VHeQjL._SX323_BO1,204,203,200_.jpg" class="table-img">
                                    </div>
                                    <div class="small-10 column">
                                       <h4><a href="history-review-details.php">This is longer content Donec id elit non mi porta gravida at eget metus.</a></h4>
                                       <small>Coupon Code: 345580238530</small>
                                    </div>
                                 </div>
                              </td>
                              <td><time>8 Jun, 2015</time></td>
                              <td><span class="text-red">Pending</span></td>
                              <td><span class="text-green">Done</span></td>
                           </tr>
                           <tr>
                              <td><time>3 Jun, 2015</time></td>
                              <td>
                                 <div class="row collapse">
                                    <div class="small-2 column text-center">
                                       <img src="http://ecx.images-amazon.com/images/I/51SW8VHeQjL._SX323_BO1,204,203,200_.jpg" class="table-img">
                                    </div>
                                    <div class="small-10 column">
                                       <h4><a href="history-review-details.php">This is longer content Donec id elit non mi porta gravida at eget metus.</a></h4>
                                       <small>Coupon Code: 345580238530</small>
                                    </div>
                                 </div>
                              </td>
                              <td><time>8 Jun, 2015</time></td>
                              <td><span class="text-red">Pending</span></td>
                              <td><span class="text-green">Done</span></td>
                           </tr>
                           <tr>
                              <td><time>3 Jun, 2015</time></td>
                              <td>
                                 <div class="row collapse">
                                    <div class="small-2 column text-center">
                                       <img src="http://ecx.images-amazon.com/images/I/51SW8VHeQjL._SX323_BO1,204,203,200_.jpg" class="table-img">
                                    </div>
                                    <div class="small-10 column">
                                       <h4><a href="history-review-details.php">This is longer content Donec id elit non mi porta gravida at eget metus.</a></h4>
                                       <small>Coupon Code: 345580238530</small>
                                    </div>
                                 </div>
                              </td>
                              <td><time>8 Jun, 2015</time></td>
                              <td><span class="text-red">Pending</span></td>
                              <td><span class="text-green">Done</span></td>
                           </tr>
                        </tbody>
                     </table>
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