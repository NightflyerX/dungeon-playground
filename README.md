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
      
      A char enchanted with this state will deal double the physical output damage 
      but will recieve 150% of the damage inflicted to it. 
      Also you can hit this character twice as good.
      
# Actions

Actions are whatever a character can do. There are class abilities, things only special characters can do, physical actions, magical actions, potions and item actions and dicerolls.
      
Dicerolls are the simplest kind of actions. You won't need tier levels. Just write your formula in the hit_chance_formula field.

    Example: 
    
    1d20 + char.attributes.strength.mod + 3
    
All the other actions have tier levels (normal, expert, master and grandmaster). A character can only do an action when he meets the requirements for that level. If you make a fire magic spell you want to set a filter in fire magic under magic filters. Set fire magic to normal in the normal tier, to experte in the expert tier and so on. Now the level of the action depends on the fire magic skill of the character. If the character is master then he can perform this action in the master level. If there are no filters then all characters can access the action, if they can pay the cost.

# Effects

Actions can have effects. Effects occur after the action made damage. There can be additional damage, add a special token, add or remove a state, summon another character or add a field. These effects can be applied to the action target but also to the action executioner, or his whole group, or the whole target group.

    Example:
    
    The action makes 20 damage. There is an effect_add_damge with the 
    
    chance: 100%
    damage_type: body_magic
    damage_formula: DAMAGE * 0.5
    damage_heal: heal
    affected_damage_pool: life points
    skip_resistance: false
    target: action_executioner
    
    The action will make 20 damage and the character dealing this damage will heal by half of that, 10.
    
# Fields

Fields are areas that affect characters. They may have an owner who may have to pay the upkeep cost each round. If a state is attached to the field it will be automatically attached to every target character of the field. But more importantly you can add events to a field that will trigger at a certain time. on_round_start will trigger whenever there is a new round. You can filter the rounds so it will only trigger on new player rounds or new dungeonmaster rounds or whatever rounds you wan't (even patterns like all four rounds etc. ). Or can trigger the event whenever damage is made and then filter the event by "Activates by the field owner only". With that it activates only when the field owner makes damage. Fields are a powerful tool for buffs and debuffs and add a variety of possibilities for otherwise complicated calculations.

    Example:
    
    Go to the library->database and create a new special token called "fire token"
    Click on new field and add a character as target to it. The character is now affected by this field.
    Add a new event on_round_start and filter it player rounds only. 
    Chose add special token and select the fire token, then create the new event
    Add another event, also on_round_start but this time no filter,  then select "Add damage". Write
      {COUNT(char.special_tokens[fire token])}d4
    in the damage_formula.
    Now, when there is a new player round the target player gets a special token and every round he recieves 1d4 damage for each of these tokens.
    

    
      
