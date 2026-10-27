# web
home/landing page is index.html, it's css file is indexcss.css and an empty javascript file for it is homescript.js
then we have the location.html for the location page 
login.html is the account login page needs rework 
db.php is database connection to my xampp mysql database
catalogue2.php is the main catalogue page, the 2 is here cause it's a rework of old version and im too lazy to change this everywhere in my db php or wherever it may be 
style.css is for the catalogue2.php 
in short: 
index.html > (indexcss.css , homescript.js) 
location.html
login.html ( need rework) 
catalogue2.php > (db.php, style.css) 

to run project with database integration, set these sets of files as follows: 
1. go to : this pc > windows(c:) > xampp > htdocs 
2. there you create a folder named rr-saree (copy paste the rr saree exactly) 
3. in C:\xampp\htdocs\rr-saree\WAD Assignment Y2 SEM 1 you paste all files in the repository to that destination
4. if yall do not have wad assignment folder, create a new one with the name: (  WAD Assignment Y2 SEM 1  )
5. import the sql file on the repository on xampp
6. after all the steps: yall can run the project on browser: http://localhost/rr-saree/WAD%20Assignment%20Y2%20SEM%201/

The login and registration forms in `login.html` submit to `auth.php`, which connects through `db.php` to the `login_info` table. Open the page through the XAMPP URL above (not by opening the HTML file directly). Register a new account first; the example SQL row uses a placeholder password and cannot be used to sign in.
