# Layouts and views folder
// needs work here 

this can be changed directly from config/constants so 

you just have to redefine that instead of going and changing everything in every place.
 
 NOTE: router:
 - 
 first part of the url HAS to be the controller, second part HAS to be the action/method.
 
 NOTE: templates:
  - 
  were made in such a way that each one would be in it's one folder, in 
 case that's needed to be changed, some more advanced changes woudl be required,
 not just changing the TEMPLATES constant.
 
 NOTE: entities and repos:
  - 
  repos will contain just the save method by default, as for edit/update, delete and selects,
  there wasn't anything created since they can get way too complex, thus, a real ORM,
  should be used instead or queries should be created in another class that would
  extend abstract repo.
  - ALSO, migrations weren't created for the same reason.