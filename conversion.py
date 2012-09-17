import MySQLdb 
import MySQLdb.cursors
import os
from subprocess import call
from time import sleep

def convert(id,video):
	#call(["ls", "-l"])
	c = db.cursor()
	c.execute("UPDATE CONVERSION_Progress SET toConvert = 0, inProgress = 1 WHERE conversion_id = %s", (str(id)))
	db.commit()
	c.close()

	logfile = "/srv/ffmpeg_log/"

	
	call("mkdir /var/www/html/marcel/view/" + video, shell=True)
	call("ffmpeg -i /srv/src/marcel2_orig/" + video + " -vcodec libx264 -threads 16 -b 250k -bt 50k -acodec libfaac -ab 56k -ac 2 -s" + " 720X480 " + "/var/www/html/marcel/view/" + video + "/" + video + ".mp4 2>" + logfile + video + ".mp4.txt 3> /dev/null 4>&1 &" , shell=True)
	call("ffmpeg -i /srv/src/marcel2_orig/" + video + " -b 250k -vcodec libvpx -threads 16 -acodec libvorbis -ab 160000 -f webm -g 30 -s" + " 720X480 " + "/var/www/html/marcel/view/" + video + "/" + video + ".webm 2>" + logfile + video + ".webm.txt 3> /dev/null 4>&1 &" , shell=True)
	call("ffmpeg -i /srv/src/marcel2_orig/" + video + " -b 250k -threads 16 -vcodec libtheora -acodec libvorbis -ab 160000 -g 30 -s" + " 720X480 " + "/var/www/html/marcel/view/" + video + "/" + video + ".ogv 2>" + logfile + video + ".ogv.txt 3> /dev/null 4>&1 &" , shell=True)
	call("ffmpeg -i /srv/src/marcel2_orig/" + video + " -vframes 1 -an -r 1 -an -ss 00:00:10 -y /var/www/html/marcel/view/" + video+ "/" + video + ".jpg >/dev/null 2>&1 &", shell=True)


	
def checkprogress(id,video,video_id):
	#print "Testing " + str(id)
	video_mp4 = video + ".mp4.txt"
	video_webm = video + ".webm.txt"
	video_ogv = video + ".ogv.txt"


	#Because of the 2nd command, we can't check mp4 anymore but that shouldn't matter as it is always the quickest.
	#x = readfile(video_mp4)
	x = 0;
	y = readfile(video_webm)
	z = readfile(video_ogv)
	if (x == 0) and (y == 0) and (z == 0):
		call("qt-faststart /var/www/html/marcel/view/" + video + "/" + video + ".mp4 /var/www/html/marcel/view/" + video + "/" + video + "out.mp4", shell=True)
		
		c = db.cursor()
		c.execute("UPDATE CONVERSION_Progress SET inProgress = 0 WHERE conversion_id = %s", str(id))
		c.execute("UPDATE VIDEO_Upload_data SET complete = 1 WHERE video_id = %s", (video_id))
		db.commit()
		c.close()

		print "Done"
	else:
		print "Waiting"



def transfer(id,conversion_id):
	marcel = MySQLdb.connect(host="girdwood.asap.um.maine.edu", user="kenai", passwd="AsAp4U2u", db="mediaserver", cursorclass=MySQLdb.cursors.DictCursor)
	c = db.cursor()
	m = marcel.cursor()

	c.execute("SELECT * FROM VIDEO_Upload_data  JOIN VIDEO_Category_map ON VIDEO_Upload_data.video_id = VIDEO_Category_map.video_id JOIN META_Category ON VIDEO_Category_map.category_id = META_Category.category_id WHERE VIDEO_Upload_data.video_id = %s", (str(id)))

	rows = c.fetchall()
	for row in rows:
		title = row['title']
		video_id = row['unique_id']
		category = row['category_id']
		category_name = row['name']
		duration = row['duration'];
		description = row['description']

		filename = row['unique_id'] + "out.mp4"
		filepath = "/var/www/html/marcel/view/" + str(row['unique_id']) + "/" + filename
		image = row['unique_id'] + ".jpg"
		imagepath = "/var/www/html/marcel/view/" + str(row['unique_id']) + "/" +image

		remotepathvideo = "kenai/" + filename
		remotepathimage = "kenai/" + image

		#grab duration from checkstatus later
		m.execute("INSERT INTO MS_video VALUES (NULL, %s, 'NULL', %s, %s, '-1', '-1', %s, 'NULL', 0, 0, 0, 0, %s, 10, '0', %s, 0)", [(str(title)), (str(description)), category, (str(remotepathvideo)), (str(duration)), (str(remotepathimage))])
		

		m.execute("SELECT * FROM MS_category WHERE id = %s", [(str(category))])

		category_result = m.fetchone();
		if(category_result == None):
			m.execute("INSERT INTO MS_category VALUES (%s, 10, %s, -1)", [(str(category)), (str(category_name))])


		remote_id = m.lastrowid
		print remote_id
	
		c.execute("UPDATE CONVERSION_Progress SET remote_id = %s WHERE conversion_id = %s", [remote_id, (str(conversion_id))])

	
		filemove = "scp " + filepath + " kenai@girdwood.asap.um.maine.edu:/Library/WebServer/Documents/marcel/mediaserver/kenai/" + row['unique_id'] + "out.mp4"
		imagemove = "scp " + imagepath + " kenai@girdwood.asap.um.maine.edu:/Library/WebServer/Documents/marcel/mediaserver/kenai/" + row['unique_id'] + "out.mp4.jpg"
		os.system(filemove)
		os.system(imagemove)

	
		c.execute("UPDATE CONVERSION_Progress SET toTransfer = 0, onMarcel = 1 WHERE conversion_id = %s", (str(conversion_id)))

	c.close()
	db.commit()
	m.close()
	marcel.commit()

def modify(id, remote, conversion_id):
	marcel = MySQLdb.connect(host="girdwood.asap.um.maine.edu", user="kenai", passwd="AsAp4U2u", db="mediaserver", cursorclass=MySQLdb.cursors.DictCursor)
	c = db.cursor()
	m = marcel.cursor()

	c.execute("SELECT * FROM VIDEO_Upload_data  JOIN VIDEO_Category_map ON VIDEO_Upload_data.video_id = VIDEO_Category_map.video_id JOIN META_Category ON VIDEO_Category_map.category_id = META_Category.category_id WHERE VIDEO_Upload_data.video_id = %s", (str(id)))
	rows = c.fetchall()

	for row in rows:
		title = row['title']
		video_id = row['unique_id']
		category = row['category_id']
		category_name = row['name']
		description = row['description']

		print category;

		m.execute("SELECT * FROM MS_category WHERE id = %s", [(str(category))])
		category_result = m.fetchone();
		if(category_result == None):
			m.execute("INSERT INTO MS_category VALUES (%s, 10, %s, -1)", [(str(category)), (str(category_name))])

		m.execute("UPDATE MS_video SET title = %s, description = %s, category1 = %s WHERE id = %s", [(str(title)), (str(description)), category, (remote)])
		c.execute("UPDATE CONVERSION_Progress SET toTransfer = 0 WHERE conversion_id = %s", (str(conversion_id)))

	c.close()
	db.commit()
	m.close()
	marcel.commit()
	marcel.close()

def delete(id,remote):

	c = db.cursor()
	m = marcel.cursor()

	m.execute("DELETE FROM MS_video WHERE id = %s", (str(remote)))
	c.execute("UPDATE VIDEO_Upload_data SET onMarcel = 0 WHERE id = %s", (str(id)))

	c.close()
	db.commit()
	m.close()
			
def main():
	cursor = db.cursor()
	stmt = "SELECT * FROM CONVERSION_Progress LEFT JOIN VIDEO_Upload_data ON CONVERSION_Progress.video_id = VIDEO_Upload_data.video_id"
	cursor.execute(stmt)

	rows = cursor.fetchall ()
	for row in rows:
		id = row['conversion_id']
		video = row['unique_id']
		remote = row['remote_id']
		if row['toConvert'] == 1:
			convert(id,video)
			print "convert"
		elif row['inProgress'] == 1:
			checkprogress(id,video,row['video_id'])
			print "checkprogress"
		elif row['toTransfer'] == 1 and row['toConvert'] == 0 and row['inProgress'] == 0 and row['onMarcel'] == 0:	
			transfer(row['video_id'], id)
		elif row['toTransfer'] == 1 and row['toConvert'] == 0 and row['inProgress'] == 0 and row['onMarcel'] == 1:
			modify(row['video_id'], remote, id)
		#elif row['toDelete'] == 1 and remote != '':
		#	delete(id, remote)
		#elif row['toModify'] ==
		#elif row['toModify'] == 1:
		#	modify(row['video_id'], remote)
		#	print "tomodify"
		#elif row['toDelete'] == 0:
		#	delete(row['video_id'], remote);
		#	print "delete"
	cursor.close()

def readfile(a):
	log = open ('/srv/ffmpeg_log/' + a, "r")
	listlines = log.readlines()
	log.close()
	listlines = listlines[-1]
	letter = listlines.split()[0][0]

	if letter == "f":
		return 1
	else:
		return 0

while True:
	db = MySQLdb.connect(host="localhost", user="blackbox", passwd="", db="blackbox", cursorclass=MySQLdb.cursors.DictCursor)
	main()
	db.close()
	sleep(15)
