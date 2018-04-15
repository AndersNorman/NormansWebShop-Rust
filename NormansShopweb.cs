using System;
using System.Collections;
using System.Collections.Generic;
using System.Globalization;
using System.Linq;
using Facepunch;
using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using Newtonsoft.Json.Linq;
using Oxide.Core;
using Oxide.Core.Configuration;
using Oxide.Core.Plugins;
using Oxide.Game.Rust;
using Rust;
using MySql.Data;
using MySql.Data.MySqlClient;
using UnityEngine;
namespace Oxide.Plugins
{
    [Info("NormansShopweb", "Anders Kristoffer Norman", 0.1)]
    [Description("Allows you to purchase items")]

    class NormansShopweb : RustPlugin
    {
        
        private MySqlConnection connection;
        private string server = "173.249.18.193";
        private string database = "s26_rust";
        private string uid = "u26_LoN7LLyxoJ";
        private string password = "QB0yO52UOZhu7BON";
        
        public double researchmoney = 500;
        public double bearkillmoney = 250;
        public double stagerkillmoney = 100;
        public double lootcratemoney = 5;
        public double craftmoney = 2;
        // The rest of the code and magic
		
		    [ChatCommand("coins")]
		    void CoinsCOmmand(BasePlayer player, string command, string[] args){
		     
		     SendReply(player,"You have "+getcoin(player.userID.ToString())+" coins.");   
		        
		    }
		    
		    
		 
		    
		    
		object OnItemResearch(ResearchTable table, Item targetItem, BasePlayer player)
        {
            
            addcoin(player.userID.ToString(),researchmoney);
            
            Puts("User '"+player.name+"' earned coins from research!");
            return null;
        }

		
		void addcoin(string uuid,double amount){
		    double money = 0;
		    double.TryParse(getcoin(uuid),out money);
		            string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                 string query = "UPDATE PLAYERS_COINS SET coins='"+(money + amount).ToString()+"' WHERE steamid='"+uuid+"'";
 
                 var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                 var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                 cmd.ExecuteNonQuery();
                 conn.Close();
		    
		}
		
		    [ChatCommand("shop")]
		  void ShopCommand(BasePlayer player, string command, string[] args){
		   
		   SendReply(player,"To purchase items go to rust.normansprojects.org. You get coins from researching.");   
		      
		  }
		
		   
		    [ChatCommand("claim")]
		    void ClaimPurchase(BasePlayer player, string command, string[] args){
		        List<string> itemstoremove = new List<string>();
		        Item[] sakerna = player.inventory.AllItems();

                 double maxitems = 30;
                if(sakerna.Count() >= 30){
                   SendReply(player,"You have no space in you're inventory!"); 
                }
                else{
                
			  
			  string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                string query = "SELECT * FROM players_orders";
 
                var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                var reader = cmd.ExecuteReader();
 
                while (reader.Read())
                {
                    
                
               
                 var someValue = reader["steamid"];
                 if(someValue.ToString() == player.userID.ToString() && sakerna.Count() < 30){
                
                  int amount;
                  int.TryParse(reader["amount"].ToString(),out amount);
                  player.GiveItem(ItemManager.CreateByName(reader["item"].ToString(),amount,0));
                  sakerna = player.inventory.AllItems();
                  itemstoremove.Add(reader["id"].ToString());
                  SendReply(player,"You claimed a item!");
                          
                 }
                 
                }
               }
               
               foreach(string s in itemstoremove){
                   string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                 string query = "DELETE FROM players_orders WHERE id="+s;
 
                 var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                 var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn); 
                 cmd.ExecuteNonQuery();
                 conn.Close();
               }
               
		    }

		

		  
		  void RemovePurchase(string uuid){
		      string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                 string query = "DELETE FROM players_orders WHERE steamid="+uuid;
 
                 var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                 var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                 cmd.ExecuteNonQuery();
                 conn.Close();
		  }
		  
		  object OnPlayerSpawn(BasePlayer player)
        {
         string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                string query = "SELECT * FROM PLAYERS_COINS";
 
                var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                var reader = cmd.ExecuteReader();
                bool didfind = false;
                while (reader.Read())
                {
                 var someValue = reader["steamid"];
                 if(someValue.ToString() == player.userID.ToString()){
                     didfind = true;
                    
                 }
                 
                 
                }
            if(!didfind){
                Puts("Making a coin entry for the user '"+player.name+"' on the Mysql Server.");
                 dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                 query = "INSERT INTO PLAYERS_COINS (steamid, coins) VALUES('"+player.userID.ToString()+"','"+500.ToString()+"')";
 
                 conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                 cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                 cmd.ExecuteNonQuery();
                 conn.Close();
            }

            return null;
        }
        
        

        
        
      
        void OnLootEntityEnd(BasePlayer player, BaseCombatEntity entity)
        {
            string theitem = entity.GetType().ToString();
            if(theitem == "LootContainer")
            {
              
                LootContainer saken;
                saken = (LootContainer)entity;
                if (saken.destroyOnEmpty)
                {
                    bool doearnmoney = true;
                    for(int i = 0; i < saken.inventorySlots; i++)
                    {
                        try{
                            if(saken.inventory.GetSlot(i).ToString() != ""){
                            doearnmoney = false;
                            }
                        }
                        catch(System.Exception e){
                            
                        }

                    }
                    if(doearnmoney){
                        Puts("Player earned coins from looting a crate which got ruined!");
                        addcoin(player.userID.ToString(),lootcratemoney);
                    }
                    else{
                    }
                }


            }

           
        }
        
        void OnEntityDeath(BaseCombatEntity entity, HitInfo info)
        {
            try
            {
                BasePlayer spelaren = info.InitiatorPlayer;
                Puts("Player killed asset '"+entity.PrefabName+"'.");
                string name = entity.PrefabName;
                if(name == "assets/rust.ai/agents/stag/stag.prefab")
                {
                    addcoin(spelaren.userID.ToString(), stagerkillmoney);
                }
                if(name == "assets/rust.ai/agents/bear/bear.prefab")
                {
                    addcoin(spelaren.userID.ToString(), bearkillmoney);
                }

                }
            catch(System.Exception e){
                
            }
        }


         void OnItemCraftFinished(ItemCraftTask task, Item item)
        {
            try{
            BasePlayer spelaren = (BasePlayer)task.owner;
            Puts(spelaren.userID.ToString() +" earned "+(craftmoney).ToString()+" coins from crafting a item.");
            addcoin(spelaren.userID.ToString(),craftmoney);
            }
            catch(System.Exception e){
                Puts("Tried doing task.owner on a none player crafted item.");
            }
        }

		  
		 string getcoin(string uuid){
		     
		     string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                string query = "SELECT * FROM PLAYERS_COINS";
 
                var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                var reader = cmd.ExecuteReader();
 
                while (reader.Read())
                {
                 var someValue = reader["steamid"];
                 
                 if(someValue.ToString() == uuid){
                     
                     return reader["coins"].ToString(); 
                     
                     
                 }
                 
                }
                return "0";
		     
		 }
		  
		 object OnPlayerRespawn(BasePlayer player)
        {
            string dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                string query = "SELECT * FROM PLAYERS_COINS";
 
                var conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                var cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                var reader = cmd.ExecuteReader();
                bool didfind = false;
                while (reader.Read())
                {
                 var someValue = reader["steamid"];
                 if(someValue.ToString() == player.userID.ToString()){
                     didfind = true;
                    
                 }
                 
                 
                }
            if(!didfind){
               
                 dbConnectionString = string.Format("server={0};uid={1};pwd={2};database={3};", server, uid, password, database);
                 query = "INSERT INTO PLAYERS_COINS (steamid, coins) VALUES('"+player.userID.ToString()+"','"+500.ToString()+"')";
 
                 conn = new MySql.Data.MySqlClient.MySqlConnection(dbConnectionString);
                conn.Open();
 
                 cmd = new MySql.Data.MySqlClient.MySqlCommand(query, conn);
                 cmd.ExecuteNonQuery();
                 conn.Close();
            }

            return null;
        }


		
    }
}