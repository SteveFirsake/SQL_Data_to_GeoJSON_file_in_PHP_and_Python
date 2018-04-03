def convert_to_geojson(items):
    import json
	#notice the structure of geoJSON below
    return json.dumps({ "type": "FeatureCollection",
                        "features": [
                                        {"type": "Feature",
                                         "geometry": { "type": "Point",
                                                       "coordinates": [ feature['_latitude'],#make sure they conform to the selected field names
                                                                        feature['_longitude']]},#make sure they conform to the selected field names
                                         "properties": { key: value
                                                         for key, value in feature.items()
                                                         if key not in ('_latitude', '_longitude') }
                                         }
                                     for feature in json.loads(items)
                                    ]
                       })

tempJSONFile = open('data.json')
tempJSONFileData = tempJSONFile.read()

geoJSONData = convert_to_geojson(tempJSONFileData)

print geoJSONData #this output will be read by the PHP script