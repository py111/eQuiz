// JavaScript Document

		function notifyUser(message){
			var str = message;
			
			str += '<br><a onclick="$(\'#notificationBar\').hide(100)" style="text-decoration:none; cursor:pointer; color:#FFF">Hide</a>';
			
			$("#notificationBar").html(str);
			$("#notificationBar").show(100);
		}
		
			function alertUser(message){
			var str = message;
			
			str += '<br><a onclick="$(\'#alertBar\').hide(100)" style="text-decoration:underline; cursor:pointer;">Hide</a>';
			
			$("#alertBar").html(str);
			$("#alertBar").show(100);
		}
		
		
		
		function createPublicQuestion(id,text, optionArray,next){
			var question = $(document.createElement('div'));	
				question.attr("class","newBox");
				
				var number = $(document.createElement('div'));
					number.attr("class","questionNumberAnswered");
				number.text((id+1) );
				question.append(number);
				
				var title = $(document.createElement('h3'));	
					title.html("Q: "+text);
					title.attr("class","titleQuestion");
					
				question.append(title);
				
				var optionTemp;
				var labelTemp;
				
				var optionTable = $(document.createElement("table"));
				optionTable.attr("align","center");
				
				$.each(optionArray,function(index,option){
					optionRow = $(document.createElement("tr"));
					optionCell1 = $(document.createElement("td"));
					
						optionTemp = $(document.createElement("input"));
						optionTemp.attr("type","radio");
						optionTemp.attr("name","option");
						optionTemp.attr("id",option['id']);
						optionTemp.attr("value",option['id']);
						
							optionTemp.attr("onclick","javascript:gradeAnswer("+option['correct']+","+next+")");
						
					optionCell1.append(optionTemp);
					
					optionCell2 = $(document.createElement("td"));
						optionCell2.text(option['text']);
						optionCell2.attr("class","questionText");
					optionRow.append(optionCell1);
					optionRow.append(optionCell2);
					
					
					optionTable.append(optionRow);
					
					
				});
				question.append(optionTable);
				
				
				$("#questionBody").append(question);
		}
		
		
		
		function createQuestion(id,text, optionArray, chosenAnswer,next,prev,chosenAnswerDB,memberTakes){
			var question = $(document.createElement('div'));	
				question.attr("class","newBox");
				
				var number = $(document.createElement('div'));
				if(chosenAnswer){
					number.attr("class","questionNumberAnswered");
				}else{
					number.attr("class","questionNumber");
				}
				number.text((id+1) );
				
				question.append(number);
				var title = $(document.createElement('h3'));	
					title.html("Q: "+text);
					title.attr("class","titleQuestion");
					
				question.append(title);
				
				var optionTemp;
				var labelTemp;
				
				var optionTable = $(document.createElement("table"));
				optionTable.attr("align","center");
				
				$.each(optionArray,function(index,option){
					optionRow = $(document.createElement("tr"));
					optionCell1 = $(document.createElement("td"));
					
						optionTemp = $(document.createElement("input"));
						optionTemp.attr("type","radio");
						optionTemp.attr("name","option");
						optionTemp.attr("id",option['id']);
						optionTemp.attr("value",option['id']);
						
						if(chosenAnswer){
							if(option['id'] == chosenAnswer){
								optionTemp.attr("checked","checked");	
							}
							optionTemp.attr("onclick","javascript:putAnswer("+option['id']+","+chosenAnswerDB+","+id+")")
						}else{
							optionTemp.attr("onclick","javascript:postAnswer("+option['id']+","+memberTakes+","+id+")")
						}
						
					optionCell1.append(optionTemp);
					
					optionCell2 = $(document.createElement("td"));
						optionCell2.text(option['text']);
						optionCell2.attr("class","questionText");
					optionRow.append(optionCell1);
					optionRow.append(optionCell2);
					
					
					optionTable.append(optionRow);
					
					
				});
				question.append(optionTable);
				
				if(prev>=0){
					buttonPrev = $(document.createElement("input"));
					buttonPrev.attr("class","input-rounded-button");
					buttonPrev.attr("type","button");
					buttonPrev.attr("value","Previous");
					buttonPrev.attr("onClick","goToQuestion("+prev+");");
					question.append(buttonPrev);
				}
				
				if(next){
					button = $(document.createElement("input"));
					button.attr("class","input-rounded-button");
					button.attr("type","button");
					button.attr("value","Next");
					button.attr("onClick","goToQuestion("+next+");");
					question.append(button);
				}
				
				$("#questionBody").append(question);
		}
		
		function postAnswer(option_idR,member_takesR,idQuestion){
			var formData = {option_id:option_idR,member_takes:member_takesR};
			$.ajax({
				url : "./web_services/memberTakesTest.class.php",
				type: "POST",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					var chosenAnswer = data;
					questions[idQuestion]["chosen_answer_dbID"] = chosenAnswer;
					questions[idQuestion]["chosen_answer"] = option_idR;
					createTableQuestions();
					goToQuestion(idQuestion);
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 		alertUser("error"+textStatus);
				}
			});
			
		}	
		
		function putAnswer(optionID,chosenAnswerDB,questionID){
			var formData = {option_id:optionID,chosen_answer_id:chosenAnswerDB};
			$.ajax({
				url : "./web_services/memberTakesTest.class.php",
				type: "PUT",
				data : formData,
				success: function(data, textStatus, jqXHR)
				{
					questions[questionID]["chosen_answer"] = optionID;
				},
				error: function (jqXHR, textStatus, errorThrown)
				{
			 		alertUser("error"+textStatus);
				}
			});
		}