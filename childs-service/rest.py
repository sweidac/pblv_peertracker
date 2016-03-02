from tinydb import TinyDB, Query
from flask import Flask, request, abort
import json


app = Flask(__name__, static_url_path='')
db = TinyDB('db.json')

@app.route("/")
def home():
  return app.send_static_file('index.html')

@app.route("/childs/", methods=['GET'])
def findAll():
  return json.dumps(db.all())

@app.route("/childs/", methods=['POST'])
def create():
  child = request.get_json(force=True)
  if not all (k in child for k in ("name", "img", "mac")):
    abort(400)
  db.insert(child)
  return json.dumps(child)

@app.route("/childs/<mac>")
def findOne(mac):
  Child = Query()
  child = db.get(Child.mac == mac)
  if not child:
    abort(404)
  return json.dumps(child)

@app.route("/childs/<mac>", methods=['DELETE'])
def delete(mac):
  child = findOne(mac)
  Child = Query()
  db.remove(Child.mac == mac)
  return child

@app.route("/childs/", methods=['DELETE'])
def deleteAll():
  db.remove(Query().mac.exists())
  return ""

app.run(port=7777)
