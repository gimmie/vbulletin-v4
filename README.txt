Gimmie Loyalty Program - Makes Loyalty Fun
Created by Gimmie (Gimmie on vBulletin.org)
---------------------------------------------

* Gimmie Loyalty Program *
------------------------------
Fancy a loyalty program for your vBulletin forum? Want to engage your users and at the same time make them loyal, happy and repeat users?  

Gimmie Loyalty Program is a simple and fun way for you to create and manage a loyalty program you can call your own. You can now incentivize your users to participate more in forum activities such as posting, voting and giving referrals.  It also allows you to set the incentive based on specific keywords or particular forum. 

Gimmie Loyalty Program integrates seamlessly with your site design and a rich catalogue of rewards that will delight your users. Reward your most loyal users today,  the Gimmie Rewards Catalogue enables them to redeem from a range of rewards after earning points on your site - product vouchers, discount coupons and products from a list of select merchants. 

* INSTALLATION *
-----------------------

1) Upload the contents of the 'Upload' folder to your forum's root.
	(If your forum's location is http://www.example.com/forums/, the root is /forums/)

2) Create a vBulletin Game and complete the registration in Gimmie Portal (https://portal.gimmieworld.com/games/new)

3) In ACP -> Plugins & Products -> Manage Products -> Add/Import Product and put
   ./XML/product_gimmie.xml
   in the "OR import the XML file from your server" box and click

4) Go to ACP -> Settings -> Options

5) Configure the setting under Gimmie Loyalty Program Setting. Fill in the Gimmie KEY and Gimmie Secret with the Game Key and Secret found in Gimmie Portal after creating the Game and update the option accordingly

6) To add the Rewards button, Search << ul id="navtabs" >> under Styles & Templates > Search in Template. 

Click on "navbar" file. Find << <ul id="navtabs" class="navtabs .... >> in the Template field, add << <li><a href="javascript:;" gm-view="catalog" class="navtab"><span>Rewards</span></a></li> >> just above the </ul> 

e.g. 

<ul id="navtabs" class="navtabs floatcontainer<vb:if condition="$show['member'] AND $notifications_total"> notify</vb:if>">
		{vb:raw template_hook.navtab_start}
		{vb:raw navigation}
		{vb:raw template_hook.navtab_end}
    <li><a href="javascript:;" gm-view="catalog" class="navtab"><span>Rewards</span></a></li>
</ul>

7) To add user points beside Username, Search << li class="welcomelink" >> under Styles & Templates > Search in Template. 

Click on "header" file. Find << li class="welcomelink" >> in the Template field, add << <span gm-view="profile" style="cursor:pointer;font-weight:bold;">(<span class="gimmie-user-points">...</span> points)</span> >> just above the </li>

e.g. 

<li class="welcomelink">{vb:rawphrase welcome_x_link_y, {vb:raw bbuserinfo.username}, {vb:link member, {vb:raw bbuserinfo}}}<span gm-view="profile" style="cursor:pointer;font-weight:bold;">(<span class="gimmie-user-points">...</span> points)</span></li>


* UPGRADE INSTRUCTIONS *
----------------------------------------
1) Upload the contents of the 'Upload' folder to your forum's root.
   (If your forum's location is http://www.example.com/forums/, the root is /forums/)

2) Import the product XML file (product_gimmie.xml) into the Product Manager in AdminCP.
   (The XML file is located in the /XML folder)


* Removal *
----------------
1) Uninstall the product in ACP -> Plugins & Products -> Manage Products.

2) Delete the plugins/gimmie folder from your server.

3) Delete the gimmie-connect.php file from your server.



* History (Changelog) *
------------------------------
1.0.0 (Jun 11, 2013)
- Initial Release

1.0.1 (Jun 12, 2013)
- Remove auto select of country due to inconsistent connectivity

1.0.2 (Jun 16, 2013)
- Added Custom CSS, Web Responsive, Auto Insert Widget on setting, update auto select of country plugin. 

1.1.0 (Nov 5, 2013)
- Switch to new widget version 2. 
- Remove "Insert Widget Root".
- Use asyn method to call API

1.2.0 (Feb 3, 2014)
- Add "Gimmie Key", "Secret Key", "Locale" field
- Add Widget Setting

1.2.1 (Jul 22, 2014)
- Update event name to trigger

