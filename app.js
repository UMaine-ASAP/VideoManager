var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , exec = require('child_process').exec
  , util = require('util')
  , Files = {}
  , mysql = require('mysql')
  , crypto = require('crypto');


//Setup all of our connections
app.listen(8080);

var database = mysql.createConnection({
	host : 'localhost',
	user: 'blackbox',
	password: '',
	database: 'blackbox',
});

database.connect(function(err) {
	if(err){
		console.log(err);
	}
	else {
		console.log("Database Connection: Success");
	}
});


function handler (req, res) {
  fs.readFile(__dirname + '/index.html',
  function (err, data) {
    if (err) {
      res.writeHead(500);
      return res.end('Error loading index.html');
    }
    res.writeHead(200);
    res.end(data);
  });
}

io.sockets.on('connection', function (socket) {
  	socket.on('Start', function (data) { //data contains the variables that we passed through in the html file
			var Name = data['Name'];

			var type = data['Type'].split('/');

			//if(type[0] == "video"){
				Files[Name] = {  //Create a new Entry in The Files Variable
					FileSize : data['Size'],
					FileType : data['Type'],
					Data	 : "",
					Downloaded : 0
				}
				var Place = 0;
				try{
					var Stat = fs.statSync('Temp/' +  Name);
					if(Stat.isFile())
					{
						Files[Name]['Downloaded'] = Stat.size;
						Place = Stat.size / 10485760;
					}
				}
		  		catch(er){} //It's a New File
				fs.open("Temp/" + Name, 'a', 0755, function(err, fd){
					if(err)
					{
						console.log(err);
					}
					else
					{
						Files[Name]['Handler'] = fd; //We store the file handler so we can write to it later
						socket.emit('MoreData', { 'Place' : Place, Percent : 0 });
					}
				});
			//}
			//else {
			//	socket.emit('Error', {'Message' : 'Selected file is not a video'});
			//}
	});
	
	socket.on('Upload', function (data){
			var Name = data['Name'];

			Files[Name]['Downloaded'] += data['Data'].length;
			Files[Name]['Data'] += data['Data'];

			if(Files[Name]['Downloaded'] == Files[Name]['FileSize']) //If File is Fully Uploaded
			{
				fs.write(Files[Name]['Handler'], Files[Name]['Data'], null, 'Binary', function(err, Writen){
					

					var inp = fs.createReadStream("Temp/" + Name);
					
					var md5sum = crypto.createHash('md5')
					
					inp.on('data', function(d){
						md5sum.update(d);
					});

					inp.on('end', function() {
						Files[Name]["FileMD5"] = md5sum.digest('hex');
					});


					var out = fs.createWriteStream("Video/" + Name);
					util.pump(inp, out, function(){

						var sql = "INSERT INTO uploads (title, mime_type, filesize, md5) VALUES (?, ?, ?, ?)";
						database.query(sql, [Name, Files[Name]['FileType'], Files[Name]['FileSize'], Files[Name]["FileMD5"]], function(err, results){
							if(err){
								console.log(err);
							}
							else {
								console.log(results);
							}
						});

					

						fs.unlink("Temp/" + Name, function () { //This Deletes The Temporary File
							exec("ffmpeg -i Video/" + Name  + " -ss 01:30 -r 1 -an -vframes 1 -f mjpeg Video/" + Name  + ".jpg", function(err){
								socket.emit('Done', {'Image' : 'Video/' + Name + '.jpg'});
							});
						});
					});
				});
			}
			else if(Files[Name]['Data'].length > 104857600){ //If the Data Buffer reaches 10MB
				fs.write(Files[Name]['Handler'], Files[Name]['Data'], null, 'Binary', function(err, Writen){
					Files[Name]['Data'] = ""; //Reset The Buffer
					var Place = Files[Name]['Downloaded'] / 10485760;
					var Percent = (Files[Name]['Downloaded'] / Files[Name]['FileSize']) * 100;
					socket.emit('MoreData', { 'Place' : Place, 'Percent' :  Percent});
				});
			}
			else
			{
				var Place = Files[Name]['Downloaded'] / 10485760;
				var Percent = (Files[Name]['Downloaded'] / Files[Name]['FileSize']) * 100;
				socket.emit('MoreData', { 'Place' : Place, 'Percent' :  Percent});
			}
		});
});
