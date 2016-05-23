#PHP Project 2015

Author: Eleonor Lagerkrants

##Vision Document
##Problem that is solved
The rising tide of social media enables people to express and display their thoughts and feelings for world.
But all people have some thoughts and feelings that are private and not meant for public scrutiny, but this information
still needs to be expressed. That is why diaries have existed and my project is an online Diary Application, The Initiative for Procrastination.

The user will be able to register a new user, log in with that user and write Diary Entries, the entries will be saved
and can be read whenever the user wants to.

## Use Cases

### Use Case 1: Write new entry
#### Preconditions
A user is registered and logged in.

#### Main Scenario
1. Starts when the user press the "Write a new entry" link.
2. System asks for title and the entry's content.
3. User enters title and content.
4. User press Save button.

#### Alternate Scenarios
* 3a. The user wants to exit the page
 * i. The user press the "Back to start" link.
* 4a. The entry could not be saved.
 * i. System presents error message.
 * ii. Step 2 in Main Scenario.

### Use Case 2: View existing entry

#### Preconditions
A user is registered and logged in.

#### Main Scenario
1. Starts when the user has logged in.
2. The system displays a list of the entries the user has written.
3. The user press the entry that he/she wants to view.
4. The system displays Title and Content for that Entry.
5. Ends when the user press the "Back to start" link.

## Test Cases

### Test Case 1.1: Successfully create a new entry
User creates a new entry. Entry is created and saved.
#### Input:
Title should be a string between 3 and 20 characters and the entry content should not be empty.

### Test Case 1.2: Failed to create a new entry due to empty title
User creates a new entry with an empty title.
#### Input
Empty title.
#### Output
"Title should be more than 3 characters."

### Test Case 1.3: Failed to create a new entry due to title with less than 3 characters
User creates new entry with a title that is shorter than 3 characters.
#### Input
Title with less than 3 characters.
#### Output
"Title should be more than 3 characters."

### Test Case 1.4: Failed to create a new entry due to title with more than 20 characters
User creates new entry with a title that is longer than 20 characters.
#### Input
Title with more than 20 characters.
#### Output
"Title should be less than 20 characters."

### Test Case 1.5: Failed to create a new entry due to empty content
User creates new entry with the text area empty
#### Input
Empty text area
#### Output
"Entry content cannot be empty"

### Test Case 2.1 Successfully view an existing entry
User press an item in the list of entries.
#### Output
System displays title and text in a new form.

## Installation and Configuration
* Upload files to server
* Create a data folder
* The data folder must be inaccessible to Apache but accessible to PHP
* Edit the information in Settings.php
