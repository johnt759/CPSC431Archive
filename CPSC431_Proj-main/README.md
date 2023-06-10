# CPSC 431 Project: Weight Tracker
## By John Tu and Angel Quiroga

### To-do list:
Create user account (Done!) 
  - Store username, password, gender, starting weight, & goal
  - Check if username already exists....error
  - Check for valid password....error
  - Assuming no errors, query database entering:
  - username, password, gender, starting weight, & goal <br>

Update profile picture (Done!) 
  - Check if file is valid type
  - Store file into profiles folder
  - Assuming no errors, query database entering:
  - Set image into "Image_Profile" WHERE username exists <br>

Delete user account (Done!) 
  - Check if user exists in Users, Meals, & Average_Weight
  - Assuming no errors, query database entering:
  - Delete from Users, Meals, & Average_Weight where username exists <br>
  
Create meal log (Done!) 
  - Enter the username, the meals eaten, the date of their recent meals eaten, and daily weight
  - Check if the username exists in the database
  - If the username and the date for the existing entry already exists....error
  - Assuming no errors, query database entering:
  - username, date, breakfast, lunch, dinner, daily weight
  - Afterwards, print out the complete log of meal history for all users.<br>

Calculate weekly average weight (Done!) 
  - Enter the username and the new current weight
  - Check if the username exists in the database
  - Obtain the weekly average by taking the difference of current weight and starting weight of user.
  - Threshold for maintain weight is +/- 3 lbs.
  - Feedback will indicate if the user is achieving their goal based on threshold.
  - Also indicate the status whether or not the user has met his or her goal.
  - Query the database by entering with the following:
  - username, start weight, current weight, weekly average, feedback
  - Print out the weekly average summary for the user.<br>

### (Finishing Touches) To-do list:
Ensure images uploaded are valid file types
  - PNG, JPG, BMP, and GIF are only accepted file types.

Input validation for empty text fields
  - All user input must not be left blank.
  - Valid data types (e.g. weight value can only be a numeric value)

Add visuals to enhance appearance
  - Fitness photos
  - Logo
  - Inspirational quotes
  - Header
