#!/usr/bin/env python
# coding: utf-8

# In[33]:


import pandas as pd
import serial
import re
import numpy as np
import sqlite3
portPath = "COM3"      
baud = 38400                     
timeout = 5                      
filename = "data.csv"
max_num_readings = 5
num_signals = 1
str1 = input("Enter Product ID: ");
str2 = input("Enter Item Name:  ")
 
 
 
def create_serial_obj(portPath, baud_rate, tout):
    
    return serial.Serial(portPath, baud_rate, timeout = tout)
    
def read_serial_data(serial):
   
    serial.flushInput()
    
    serial_data = []
    readings_left = True
    timeout_reached = False
    
    while readings_left and not timeout_reached:
        serial_line = serial.readline()
        if serial_line == '':
            timeout_reached = True
        else:
            serial_data.append(serial_line)
            if len(serial_data) == max_num_readings:
                readings_left = False
        
    return serial_data
 
def is_number(string):
   
    try:
        str(string)
        #float(string)
        return True
    except ValueError:
        return False
        
def clean_serial_data(data):
    
    clean_data = []
    line_data = []
    newlist1 = []
    newlist1.append(str1)
    for line in data:
        line = line.decode('ISO-8859-1')
        line = line.split('\r\n')
       
        if line != '\r\n':
            line_data = line
            
            line_data.append(str1)
            line_data.append(str2)
        else:
           
            break 
        if len(line_data) >= 2:
            clean_data.append(line_data)
             
    return clean_data           


# In[36]:


#try:
print ("Creating serial object...")
serial_obj = create_serial_obj(portPath, baud, timeout)
#except SerialException:
  #  print("COM3 Already Active")


# In[37]:


print ("Reading serial data...")
serial_data = read_serial_data(serial_obj)
print (len(serial_data))


# In[38]:


print ("Cleaning data...")
clean_data =  clean_serial_data(serial_data)


# In[39]:


def newframefinal():
    df = pd.DataFrame(clean_data)
    start = 0  # set start to 0 for slicing
    newDF = pd.DataFrame() 
    newlist = []
    for i in range(len(df.index)):
        if (i + 1) % 2 == 0:  # the modulo operation
            result = df[0].iloc[start:i+1].values.reshape(2)
            newlist.append(result)
        #resdf = pd.DataFrame(result)
        #newDF.append(resdf)
            print (result)
        
            start = i + 1 
    global dfnew
    dfnew = pd.DataFrame(newlist,columns=['Time','temperature'])
    dfnew['ItemName']= df[3]
    dfnew['ProductID'] = df[2]
    dfnew = dfnew.set_index("ProductID")
    dfnew['temperature'] = dfnew['temperature'].astype(float)
    return dfnew


# In[40]:


newframefinal()


# In[41]:


def createdatabase():
    conn = sqlite3.connect('sensor.db')
    cursor = conn.cursor()
    print("Opened database successfully")
    try:
        dfnew.to_sql('sensortable',conn)
    except ValueError:
        print('already exists')
        sql = ''' INSERT INTO sensortable(ProductID,Time,temperature,ItemName)
              VALUES(?,?,?,?) '''
    cur = conn.commit()
    cur = conn.cursor()
    conn.executemany(sql, dfnew.to_records(index=True,)) 
    dfsql = pd.read_sql_query("SELECT * FROM sensortable", conn)
    conn.close()
    return dfsql


# In[42]:


createdatabase()


# In[ ]:




