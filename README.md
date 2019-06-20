# time-registration
App for work hour management on projects

### Login UI
Initial landing platform, this is for logging in and assigning a session,
which is used for determaning which employee is making the time registration.

![Snaptime login](https://user-images.githubusercontent.com/28634497/59848793-57c53500-9366-11e9-8ced-3a85a3831c93.png)

### Form UI
After logging in this ui is accessible and the user can now start a time registration.

![Snaptime timereg form](https://user-images.githubusercontent.com/28634497/59848869-7fb49880-9366-11e9-9076-5f3962b2cbe6.png)

### Field Breakdown

1st Field: A selection field used for determining which customer the work was done for, the options is pulled from the customer table in the database.

When marking the field a dropdown of options is displayed, this contains a search field where the user can input the customer name.
If the customer does not already exist the name can be completed and a option with that name can be selected.

The form will now display that the new customer will be created on submit.

![Snaptime kunde](https://user-images.githubusercontent.com/28634497/59848774-4d0aa000-9366-11e9-9788-bda144505aee.png)

2nd Field: Now that the user has selected a customer, this field will display options with the currently running projects under that customer.
This field works in the same way as the customer example regarding creation of new projects.

![Snaptime projekt](https://user-images.githubusercontent.com/28634497/59848817-61e73380-9366-11e9-81e5-ef0b40e21153.png)

3rd Field: A simple JQuery datepicker, mark the field and select the date on which the work was done.

4th Field: This is for writing a short description of the work done.

5th Field: Input field for the actual amount of hours used on the work, this is used for statics internally.

6th Field: Input field for the amount of hours the client should be billed for work done.

![Snaptime info](https://user-images.githubusercontent.com/28634497/59848749-3d8b5700-9366-11e9-8407-afed6166f4bb.png)

### Validation

Every field is validated live and marked with a green icon if the input is valid, otherwise the field/s with invalid input is marked with a red border and a red icon containing an x.

If all fields are filled in correctly, a creation of the registration is now possible,
and the employee can click the save button at the bottom.


### Comments
This project was created for a buisness for use internally.
Security is a consern in this project and for that reason all database information has been removed from the code contained in this repository.
This means that the project can not be downloaded and displayed live, this is merely for demonstration of work done.

