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
  
# Weapons

You can create weapons in Library->Weapons. Every weapon has a weapon type that refers to the weapon category. You can add physical or magical armor if this weapon should give you some. Additionally you can add modifiers that will affect the character that has this weapon equipped. Weapons do not have different tier levels when created. However you can set a tier level when adding a weapon to the shop. 

# Items / Equipment

You can create weapons in Library->Items/Equipment. Each equipment must have at least one tier level. Of course it is best to describe the equipments for all tier levels 1-5. One being built out of scrap and fife beeing forget with the best metal one can find. Logically a tier fife plate armor protects you much more than a tier two plate armor. 

    Example: 
    
    Plate armor Tier 5:
        char.pools.ap.add -15
        char.resistances.physical sting +10
        char.resistances.physical cut +10
        
    This armor is pretty heavy that is why not all 100 action points will be added each round. But it protects well against physical sting and cut damage. 
    
You can also define cost reduction. If an action costs a certain amount of for example mana, the equipment can help to reduce this number.

    Example:
    
    Robe Tier 3:
        affects_cost
            mana -2
        modifiers
            char.resistances.Magic arcane magic +char.skills.defensive.robe.skill
            char.resistances.Magic spiritual magic +char.skills.defensive.robe.skill
            char.pools.mana.add +char.skills.defensive.robe.skill
            
    This piece of cloth lets you perform every action for two less mana. Additionally you get protection from arcane and spiritual magics by the amount of robe skill. And each round this robe helps you regenerate your mana pool by the value of robe skill.

# The store

In Campaign->Campaign show you can drag and drop items from the library to the left into your shop. Once in the shop every character can equip the items in Characters->Character list->Shop. When a character joins the game it will bring all the stuff equipped with it. Weapons, equipment and items do not change anymore when changed in the library. They are stored there.

# Loot

In the game you can click on a character and then on the Equipment-Button. You can there see a list of your equipment. Also you can claim weapons and equipment from the shop if there is any available. When claimed the equipment is stored in your backpack and NOT ACTIVE. To use it you also have to click on "equip". The head line will change color from gray (inactive) to green (active).

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
    
For other actions the diceroll must result in 1 success or 0 fail. For that use the "<",">", ">=", "<=" operators, or use a percentual value.

    Example:
    
    1d100 <= w_tier_lvl[30,50,55,60,65,70] + attribut + w_skill * 5 - target_armor_physical
    1d100 <= 80 + m_skill - target_armor_magical
    50%
    
All the other actions that dicerolls have tier levels (normal, expert, master and grandmaster). A character can only do an action when he meets the requirements for that level. If you make a fire magic spell you want to set a filter in fire magic under magic filters. Set fire magic to normal in the normal tier, to experte in the expert tier and so on. Now the level of the action depends on the fire magic skill of the character. If the character is master then he can perform this action up to the master level. If there are no filters then all characters can access the action, if they can pay the cost.

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

Note: If you chose the on_made_damage-Event then you also have access to the "DAMAGE" keyword for your formula, where the value of the already dealt damage is stored.
    
      
