//checker of quiz
function UserQuiz() {
  this.check = function() {
    return $.ajax({url: '../user/quiz.php?r=check', type: 'post', data: {}});
  }
  this.test = function() {
    console.log('test');
  }
}