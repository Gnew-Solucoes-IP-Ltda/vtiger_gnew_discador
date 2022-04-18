import mysql.connector
from settings import DATABASE

class MysqlConn():

  def __init__(self, dictionary=False):
    self.db_connection = mysql.connector.connect(
      host=DATABASE['host'], 
      user=DATABASE['user'], 
      passwd=DATABASE['passwd'], 
      database=DATABASE['db']
    )
    self.cursor = self.db_connection.cursor(
      dictionary=dictionary
    )

  def query(self, query):
    self.cursor.execute(query)
    return self.cursor

  def set_db(self, database):
    self.db_connection = mysql.connector.connect(
      host=DATABASE['host'], 
      user=DATABASE['user'], 
      passwd=DATABASE['passwd'], 
      database=database
    )
    self.cursor = self.db_connection.cursor()  

  def commit(self):
    self.db_connection.commit()

  def disconnect(self):
    try:
      self.cursor.close()
    
    except:
      pass

    try:
      self.db_connection.close()
    
    except:
      pass