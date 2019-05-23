#!/usr/bin/env python
# coding: utf-8

# In[18]:
# from flask_wtf import FlaskForm
from wtforms import StringField, PasswordField, BooleanField, SubmitField
from wtforms.validators import DataRequired
from flask import send_file
import io
from datetime import date
import plotly
import plotly.plotly as py
import plotly.graph_objs as go
from plotly import tools
from plotly.offline import *
from flask import make_response

from matplotlib.backends.backend_agg import FigureCanvasAgg as FigureCanvas
from flask_wtf import FlaskForm
import pandas as pd
from flask import jsonify,json
import requests
import cgi
import urllib.request
import matplotlib.pyplot as plt
#get_ipython().run_line_magic('matplotlib', 'inline')
import matplotlib
import matplotlib.pyplot as plt
import base64
from bs4 import BeautifulSoup
matplotlib.use('Agg')
plt.interactive(True)
df = pd.read_csv("cfsc.csv")


# In[11]:


def RetrievingTime():
    #z = open("jnew.html",'a')
    soup = BeautifulSoup(open('/SearchProduct.html/'), 'lxml')
    input = soup.find('input')
    new = input.attrs['value']
    #time = df[df["Food Description"]=='crab'].iloc[:,2]
    #data = {'time':time}
    #data2 = pd.DataFrame(data)
    #data2.to_html("time.html")
    #data3 = pd.read_html("time.html")
    return new


# In[21]:


def crabGraph(result):
    #search = StringField('username', validators=[DataRequired()])
    #print(search)
     df['Date of Storage in retailer'] = pd.to_datetime(df['Date of Storage in retailer'])#.astype(str)#.order()


     df['Date of Storage in retailer'].sort_values()
     crab = df[df["Food Description"]==str(result)]#.order()

     #crab =  crab.sort_values(df.iloc[:,2])
     time = crab.iloc[:,2].index.values
     temp = crab.iloc[:,4]
     theta = -15
     gamma = -27
     plt.figure(num=None, figsize=(15, 10), facecolor='w', edgecolor='k')
     plt.plot(time,temp,marker = '^')
     plt.xticks(time,time,rotation = 90)
     plt.xlabel("Date")
     plt.ylabel("temperature")
     plt.title("Time vs Temperature of the product")
     plt.axhline(theta, color='red', lw=10, alpha=0.5)
     plt.axhline(gamma, color='red', lw=10, alpha=0.5)


     layout = go.Layout(title='Days vs Temperature', xaxis=dict(title='Days'), yaxis=dict(title='Temperature'))
     trace = go.Scatter(x=time, y=temp)

     data = [trace]


     fig = go.Figure(data=data, layout=layout)
     #div_output = plot(fig, output_type='div', include_plotlyjs=False)
     #div = plotly.offline.plot(data,filename='templates/base-line.html')# filename='basic-line')
     #py.iplot(data, filename='basic-line')


     graphJSON = json.dumps(fig, cls=plotly.utils.PlotlyJSONEncoder)

     return graphJSON
     img = io.BytesIO()
     plt.savefig(img,format = 'png')
     plt.close()

     #img.seek(0)
    # plot_url = base64.b64encode(img.getvalue()).decode()
    # return plot_url
     #return '<img src="data:image/png;base64,{}">'.format(plot_url)
    #graph_url = base64.b64encode(img.getvalue()).decode()
    #response = make_response(img.getvalue())
    #response.headers['Content-Type'] = 'image/png'
    #img.close()
    #return response
    #return send_file(img, mimetype='image/png')

# In[ ]:




