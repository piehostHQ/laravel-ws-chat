<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://unpkg.com/piesocket-js@5"></script>
</head>

<body>

    @vite('resources/css/app.css')

    <form id="form" class="bg-gray-100 h-screen flex flex-col ">

        <div class="flex-1 overflow-y-auto p-4">
            <div class="flex flex-col space-y-2 ">
                <div class="flex justify-end">
                    <div id="my-messages" class="text-black " >
                        <div ></div>
                    </div>
                </div>

                <div class="flex  justify-start">
                    <div id="others-messages" class="text-black w-full" >
                        <div ></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 flex items-center">
            <input type="text" placeholder="Type your message..." id="input" autocomplete="off"
                class="flex-1 border rounded-full px-4 py-2 focus:outline-none">
            <button type="submit"
                class="bg-blue-500 text-white rounded-full p-2 ml-2 hover:bg-blue-600 focus:outline-none">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                    stroke="#ffffff">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M11.5003 12H5.41872M5.24634 12.7972L4.24158 15.7986C3.69128 17.4424 3.41613 18.2643 3.61359 18.7704C3.78506 19.21 4.15335 19.5432 4.6078 19.6701C5.13111 19.8161 5.92151 19.4604 7.50231 18.7491L17.6367 14.1886C19.1797 13.4942 19.9512 13.1471 20.1896 12.6648C20.3968 12.2458 20.3968 11.7541 20.1896 11.3351C19.9512 10.8529 19.1797 10.5057 17.6367 9.81135L7.48483 5.24303C5.90879 4.53382 5.12078 4.17921 4.59799 4.32468C4.14397 4.45101 3.77572 4.78336 3.60365 5.22209C3.40551 5.72728 3.67772 6.54741 4.22215 8.18767L5.24829 11.2793C5.34179 11.561 5.38855 11.7019 5.407 11.8459C5.42338 11.9738 5.42321 12.1032 5.40651 12.231C5.38768 12.375 5.34057 12.5157 5.24634 12.7972Z"
                            stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </g>
                </svg>
            </button>
        </div>

    </form>

    <script>
        const input = document.getElementById('input');
        var username = "user_" + (Math.floor(Math.random() * 1000));
        var form = document.getElementById('form');
        form.addEventListener("submit", sendMessage);

        var piesocket = new PieSocket.default({
            clusterId: "free.blr2",
            apiKey: "B9UKgvptNTWrZxfCUTquFp7nKVsYqu2LtmBao5Jg",
            notifySelf: true,
            presence: true,
        });


        var channel;
        piesocket.subscribe("chat-room").then(ch => {
            channel = ch;

            channel.listen("system:member_joined", function(data) {
                // if (data.member.user == username) {
                //     data.member.user = "<b>You</b>";
                //     document.getElementById('my-messages').insertAdjacentHTML('afterend',
                //         `<div> ${data.member.user} joined the chat<div>`);
                // } else {
                //     document.getElementById('others-messages').insertAdjacentHTML('afterend',
                //         `<div> ${data.member.user} joined the chat<div>`);
                // }

            })

            channel.listen("new_message", function(data, meta) {
                const messageElement = document.createElement('div');
                messageElement.innerHTML = `${data.sender}: ${data.text}`;
                if (data.sender == username) {
                    document.getElementById('my-messages').appendChild(messageElement);
                } else {
                    document.getElementById('others-messages').appendChild(messageElement);
                }
            })
        });


        function sendMessage(e) {
            e.preventDefault();

            if (!channel) {
                alert("Channel is not connected yet, wait a sec");
                return;
            }

            var message = document.getElementById('input').value;

            channel.publish("new_message", {
                sender: username,
                text: message
            });
            document.getElementById('input').value = '';
        }
    </script>

</body>

</html>
