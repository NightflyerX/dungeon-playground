# dungeon-playground
The Dungeon Playground is a free digital tool to calculate your actions in dice games like Dungeon & Dragon

# Requirements:

  PHP 7.1
  mySQL any newer Version

# Installation:

  Download the content of the dungeon.zip to your computer and extract it.
  Create a new database on your server
  Edit the database access in the "dbconnect.php"-file, optionally change the SALT-constant.
  Open the "login.php" and edit the SECURITY_CODE-constant. You (and your friends) will need this code to make a new account.
  Open the "security.php" and change the "ABSOLUTE_URL"-constant to the server address where you will upload the scripts.
  Upload the scripts and import the sql-file to your database. You are done.

# The Tree

  All the information in a game is stored in the game-Object. You can have a look in the console of your browser. All the characters are   stored in game.chars. The level of the first character is therefore game.chars[0].level. If you add a state that modifies this level, the formula will look like this: 
  
        path: char.level
        modifier: +2
        
  If the character has a base level of 15 then this state will rise it to 17. ALL THE CALCULATIONS WILL NOW TAKE THIS NEW VALUE. So if this character can make damage with an attack that has the formula char.level then this char will now deal 17 damage. "char" will always refer to the player who makes the action. The target of the action can be called with "char[target]". Or you can address a certain character directly by calling it with its ID: "char[123456789]"
  
  Examples:
  
    char.attributes.strength.mod  <- The strength modifier of the current character
    char.skills.magic_types.fire_magic.skill <- The fire magic skill of the character
    char[target].armor.result_physical <- The physical armor of the target character
    
  Note: You can only access numbers in the tree. An exception are tokens where you can access the number of all tokens or the number of a specific token kind.
  
# Modifiers:

  Modifiers are pieces of a calculation that modify the values in the tree. You can do additions, substractions, divisions, multiplications, percentual values and absolute values:
  
    Examples:
    
    value 7, modifier "+2", new value 9
    value 7, modifier "-2", new value 5
    value 7, modifier "+2d4", new value 9-15
    value 7, modifier "+char.attributes.agility.mod", new value: agility modifier added
    value 7, modifier "*2", new value 14
    value 7, modifier "200%", new value 14
    value 7, modifier "10!", new value 10
    
    Note: If there are more states that modify one tree value then these states are processed in the order they are created. You should avoid multiplications and divitions on values you also add or substract. 
    
# States

    States are the parent level of modifiers. You can create states by clicking on the character in the game and then the screwdrive symbol. Or you can load a stored state by clicking the plus symbol. A state can contain more than one modifiers. 
    
    Example:
    
      State name: Berserk
      char.globals.physical_damage_output *2
      char.globals.physical_damage_input *1.5
      char.globals.get_hit_chance_value *2
      
      A char enchanted with this state will deal double the physical output damage but will recieve 150% of the damage inflicted to it. Also you can hit this character twice as good.
      

