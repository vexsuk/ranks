<html>
    <head>
        <title>vEXS Validation Tool</title>
        <link rel="icon" href="images/favicon.ico">
    </head>
    <body>
        <div id="wrapper">
            <div id="contents">
                <h1 class="title is-1 has-text-centered">vEXS Rank Checker</h1>
                <div class="idInput">
                    <p class="has-text-centered">Input your EXS ID and we'll lookup your current rank and give you your new rank!</p>
                    <p class="has-text-centered">Lookup not working? Try switching to manual input.</p>
                </div>
                <div class="manualInput is-hidden">
                    <p class="has-text-centered">Enter your details manually from <a href="https://vamsys.io/profile">your vAMSYS profile</a> and we'll give you your current and new rank!</p>
                    <p class="has-text-centered"><strong>NOTE:</strong> "Points" is your PIREP points without any bonus points.</p>
                </div>
                <br>
                <form id="form" method="POST" action="./validate">
                    <div class="fields idInput">
                        <div class="field">
                            <label class="has-text-centered label">Pilot ID</label>
                            <div class="field-body has-addons">
                                <a class="button is-static">
                                    EXS
                                </a>
                                <div class="field">
                                    <input id="pilotId" class="input textBox" type="text" placeholder="0536" name="pilotId">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="manualInput" class="fields is-hidden manualInput">
                        <div class="field">
                            <label class="has-text-centered label">Hours</label>
                            <div class="field-body">
                                <div class="field">
                                    <input id="hours" class="input textBox" type="text" placeholder="Hours" name="hours">
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="has-text-centered label">PIREP Points</label>
                            <div class="field-body">
                                <div class="field">
                                    <input id="points" class="input textBox" type="text" placeholder="Points" name="points">
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="has-text-centered label">Bonus Points</label>
                            <div class="field-body">
                                <div class="field">
                                    <input id="bonus" class="input textBox" type="text" placeholder="Bonus Points" name="bonus">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="has-text-centered">
                    <button id="switchButton" class="button has-text-centered is-info">Switch to manual input</button>
                </div>
                <div id="ranksContainer">
                    <div class="rankContainer has-text-centered">
                        <p><strong>Old Rank</strong></p>
                        <p id="oldRank">Enter your details!</p>
                        <img id="oldEpaulette" src="./public/images/loading.gif"/>
                    </div>
                    <div class="rankContainer has-text-centered newRankContainer">
                        <p><strong>New Rank</strong></p>
                        <p id="newRank">Enter your details!</p>
                        <img id="newEpaulette" src="./public/images/loading.gif" />
                    </div>
                </div>
            </div>
        </div>
    </body>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.css">
    <link rel="stylesheet" type="text/css" href="./public/stylesheets/style.css"></script>
    <script src="./public/javascripts/formHandling.js"></script>
</html>