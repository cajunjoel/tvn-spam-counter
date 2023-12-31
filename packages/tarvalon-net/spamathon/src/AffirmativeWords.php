<?php 
namespace TarValonNet;

$affirmative_words = array(
  "able", "absolutely", "abundance", "abundant", "accepted", "accepting",
  "accessible", "acclaimed", "accommodative", "accomplished", "accomplishment",
  "accurate", "achieve", "achievement", "achiever", "acknowledged", "active",
  "activism", "acts of kindness", "adaptable", "adaptive", "adept", "admirable",
  "admire", "admired", "adoptive", "adorable", "adore", "adulation",
  "adventurous", "affability", "affable", "affection", "affectionate",
  "affirmations", "affluent", "agathist", "agreeable", "alert", "aligned",
  "alive", "alluring", "amaze", "amazing", "ambitious", "ameliorate", "amiable",
  "amicable", "amuse", "amusement", "analytical", "angelic", "animate",
  "animated", "appealing", "appreciate", "approachable", "approve", "april",
  "aptitude", "articulate", "artistic", "ascendant", "aspiring", "assertive",
  "associative", "assurance", "assure", "assured", "astonishing", "astounding",
  "astute", "athletic", "attentive", "attractive", "august", "auspicious",
  "authentic", "awake", "aware", "awareness", "awesome", "balanced", "baronial",
  "bashful", "bastion", "beaming", "beautiful", "beauty", "befriend",
  "beguiling", "believe", "belle", "beloved", "beneficial", "benevolent",
  "benignant", "best", "best friend", "bestie", "bewitching", "big-hearted",
  "blessed", "bliss", "blissful", "blithesome", "bold", "booming", "boss",
  "bountiful", "brainy", "brave", "bravery", "bravo", "breathtaking", "bright",
  "brighten", "brightsiding", "brilliance", "brilliant", "brisk",
  "broad-minded", "bubbly", "build", "buoyant", "calm", "calming", "candid",
  "capable", "captivating", "care", "careful", "caring", "categories",
  "cautious", "celebrate", "celebration", "centered", "certain", "champ",
  "champion", "changeable", "charismatic", "charitable", "charity", "charm",
  "charming", "cheer", "cheerful", "cherish", "chic", "childlike", "chipper",
  "chivalrous", "chummy", "classy", "clean", "clear", "clear-thinking",
  "clever", "colorful", "comfortable", "comforting", "comical", "committed",
  "communicative", "compassion", "compassionate", "competent", "competitive",
  "complement", "complete", "compliments", "confident", "congenial",
  "congratulations", "connected", "conscientious", "conscious", "consciousness",
  "conservation", "conservationists", "conserve", "considerate", "consistent",
  "constant", "constructive", "content", "contribute", "controversial",
  "convenient", "cooperative", "coordinate", "correct", "courage", "courageous",
  "courteous", "create", "creative", "creativity", "credibility", "credible",
  "cultivate", "cultivated", "cure", "curious", "customary", "dancer", "daring",
  "darling", "dazzle", "dazzling", "dear", "dearest", "december", "decided",
  "dedicated", "defender", "definite", "delectable", "delicious", "delight",
  "delightful", "dependable", "deserving", "desirable", "determined", "develop",
  "developed", "devoted", "devotion", "differentiate", "dignified", "dignify",
  "diligent", "diplomatic", "direct", "discerning", "discipline", "disciplined",
  "discover", "discreet", "display", "distinguished", "diva", "diversify",
  "diversity", "divine", "dolly", "donation", "donor", "down to earth",
  "down-to-earth", "dream", "dreamland", "dreamy", "driven", "dutiful",
  "dynamic", "dynamite", "dynamo", "eager", "earnest", "ease", "easy", 
  "easy going", "easy-going", "ecstatic", "educated", "education", "effective",
  "efficient", "effortless", "elated", "elation", "electrifying", "elegant",
  "elicit", "eloquent", "eminent", "emotional", "empathetic", "empathy",
  "employable", "empower", "empowered", "empowerment", "enchanting",
  "encouraged", "endearing", "endless", "enduring", "energetic", "energy",
  "engaging", "enhance", "enhancer", "enjoy", "enjoyable", "enlightened",
  "enormous", "enrich", "enriching", "enterprise", "enterprising", "entertain",
  "entertaining", "enthusiasm", "enthusiastic", "enticing", "epic", "equality",
  "especial", "essential", "establish", "esteem", "eternal", "ethical",
  "euphoria", "euphoric", "everlasting", "evolve", "exalt", "exceed", "excel",
  "excellence", "excellent", "exceptional", "excite", "excited", "excitement",
  "exciting", "exemplary", "exhilarating", "expand", "expectant", "experienced",
  "experiment", "expert", "explore", "exquisite", "extensive", "extraordinary",
  "extravagance", "exuberant", "exultant", "ezoic", "fab", "fabulous",
  "facilitate", "facilitator", "fain", "fair", "fair minded", "fair-minded",
  "faith", "faithful", "familiar", "famous", "fanatic", "fancy", "fantastic",
  "fantasy", "farewell", "fascinating", "fashionable", "fast", "fate",
  "favorable", "favorite", "fearless", "february", "feel good", "feel-good",
  "feminism", "fervent", "festive", "festivities", "fetching", "fiancee",
  "fidelity", "fierce", "fiery", "fine", "finesse", "first", "fit", "fitness",
  "flags", "flamboyant", "flattering", "flawless", "flexible", "flourish",
  "flourishing", "focused", "fondness", "forever", "forgiveness", "forgiving",
  "forthright", "fortuitous", "fortunate", "fortune", "frank", "free", 
  "free spirited", "free-spirited", "freedom", "fresh", "friend", "friendliness",
  "friendly", "fruit", "fulfill", "fulfilled", "fulfilling", "fun", 
  "fun loving", "fun-loving", "funny", "gain", "gallant", "gameplan", "gay",
  "genealogy", "generate", "generous", "genius", "gentle", "gentleman",
  "genuine", "gift", "gifted", "gifts", "giveaway", "giving", "giving back",
  "glad", "glam", "glamorous", "glamour", "gleaming", "glee", "glittering",
  "glorify", "glorious", "glowing", "gnarly", "goal", "good", "good news", 
  "good looking", "good-looking", "goodhearted", "goodwill", "gorgeous", "gourmet",
  "grace", "graceful", "gracious", "grammy", "grand", "grandiose", "grateful",
  "gratify", "gratitude", "great", "greatest", "greatness", "green", "grinning",
  "groom", "grow", "growing", "growth", "guardian", "gumptious", "hallelujah",
  "handful", "handsome", "happiness", "happy", "hard worker", "hardworking",
  "hardy", "harmonious", "harmony", "harvest", "heal", "healed", "health",
  "healthy", "heart", "heaven", "heavenly", "heir", "hello", "help", "helper",
  "helpers", "helpful", "hero", "heroic", "heroism", "high", "high minded",
  "high-minded", "highlight", "hilarious", "hobby", "holidays", "holy", "home",
  "hone", "honest", "honor", "honorable", "hope", "hopeful", "hoping",
  "hospitable", "hug", "huge", "humane", "humanitarian", "humble", "humility",
  "humor", "humorous", "hyper", "hypnotic", "ideal", "idealistic", "idyllic",
  "illuminate", "imaginative", "immaculate", "immense", "impartial",
  "impeccable", "important", "impress", "impressive", "improve", "inaugurate",
  "incisive", "inclusive", "incomparable", "increase", "incredible",
  "independent", "indigenous", "indispensable", "individual", "individualistic",
  "indulge", "industrious", "ineffable", "infinite", "informal", "infuse",
  "ingenious", "ingenuity", "initiator", "innovative", "inquisitive",
  "insightful", "inspiration", "inspirational", "inspire", "inspired",
  "inspiring", "instinctive", "integrate", "integrity", "integrous",
  "intellectual", "intelligence", "intelligent", "intense", "intensify",
  "interest", "interested", "interesting", "intrepid", "intrigued", "intuitive",
  "invaluable", "inventive", "investigate", "invigorating", "invincible",
  "inviting", "irreplaceable", "irresistible", "jacked", "jackpot", "jammy",
  "january", "java", "jaw dropping", "jaw-dropping", "jazz", "jeez", "jelly",
  "jellyfish", "jesting", "jettison", "jewel", "jig", "jiggle", "job", "join",
  "joker", "jolly", "journal", "jovial", "joy", "joyful", "joyous", "joysome",
  "jubilant", "jubilee", "judicious", "juggler", "juicy", "july", "jumbo",
  "june", "jurassic", "just", "justice", "justify", "juvenile", "kabab",
  "kaleidoscope", "kangaroo", "karate", "karma", "keen", "keep", "key",
  "kickstart", "kidding", "kids", "kimono", "kind", "kind heart", 
  "kind hearted", "kind-heart", "kind-hearted", "kindergarten", "kindness", "kindred",
  "king", "kiss", "kitten", "kitty", "knight", "knightly", "knockout", "know",
  "knowing", "knowledge", "knowledgeable", "kudos", "kwanzaa", "laid back",
  "laid-back", "lambent", "large", "laudable", "laugh", "laughter", "lavish",
  "leader", "leading", "learn", "learning", "legacy", "legend", "legendary",
  "leisure", "leisurely", "level headed", "level-headed", "lgbtq+", "liberate",
  "liberty", "life", "lifesaver", "light", "light hearted", "light-hearted",
  "lighten", "lighthearted", "likable", "likely", "limitless", "lineage",
  "lion", "lionhearted", "listener", "literate", "lively", "logical", "lol",
  "longevity", "lovable", "love", "lovely", "loving", "loyal", "loyalty",
  "luck", "lucky", "lullaby", "luminous", "lustrous", "luxurious", "luxury",
  "lyrics", "maestro", "magic", "magical", "magnanimous", "magnetic",
  "magnificent", "maiden", "majestic", "major", "make", "mama", "manners",
  "march", "marvel", "marvellous", "marvelous", "massive", "masterful",
  "masterpiece", "mature", "maximize", "may", "meaning", "meaningful",
  "mediator", "medicine", "meditative", "melody", "memento", "mend", "mercy",
  "meritorious", "merry", "methodical", "meticulous", "mighty", "migrate",
  "mild", "mindful", "mindfulness", "miracle", "miraculous", "mirthful",
  "moderate", "modern", "modest", "mojo", "mood", "moral", "morale", "mother",
  "motivated", "moving", "muse", "musical", "mutual", "mutual aid",
  "mysterious", "native", "natural", "nature", "neat", "necessary", "nectar",
  "needed", "neighbor", "neighborly", "nest", "news", "newspaper", "nice",
  "nifty", "noble", "nominated", "nonjudgmental", "nonprofit", "nook",
  "nourish", "nourishing", "nourishment", "november", "nubile", "numinous",
  "nurse", "nurture", "nurturing", "nutrient", "nutritious", "nuzzle", "oasis",
  "obedient", "objective", "obliging", "observant", "obtain", "october",
  "omnipotent", "omnipresent", "on cloud nine", "on top of world", "onward",
  "open", "open minded", "open-hearted", "open-heartedly", "open-minded",
  "opportune", "opportunity", "optimism", "optimistic", "optimum", "opulence",
  "opulent", "orderly", "organic", "organized", "out of this world", "original",
  "out-of-this-world", "outdone", "outgoing", "outperform", "outstanding",
  "overachiever", "overcome", "overjoy", "overjoyed", "overture", "pacifism",
  "pally", "paradise", "paramount", "particular", "partner", "passion",
  "passionate", "patience", "patient", "patiently", "patriotic", "peace",
  "peaceful", "peacekeeper", "peachy", "peppy", "perceive", "perceptive",
  "perfect", "perfection", "perform", "perky", "persevering", "persist",
  "persistent", "personable", "persuasive", "pertinent", "phenomenal",
  "philanthropic", "picturesque", "pioneer", "piquant", "placate", "playful",
  "pleasant", "pleasing", "pleasure", "plentiful", "plenty", "poised",
  "polished", "polite", "politeness", "political", "posh", "positive",
  "positivity", "power", "powerful", "practical", "pragmatic", "praising",
  "precious", "precise", "premium", "prepared", "pretty", "prevail", "pride",
  "prime", "principle", "prize", "prized", "proactive", "prodigy", "productive",
  "professional", "profound", "progress", "progressive", "promise", "promising",
  "prompt", "propitious", "prosperous", "protective", "proud", "prune",
  "punctual", "pure", "purify", "purpose", "quaint", "qualified", "quality",
  "queen", "queenlike", "queenly", "quell", "quick", "quick acting", 
  "quick minded", "quick moving", "quick thinking", "quick witted", "quick-acting",
  "quick-minded", "quick-moving", "quick-thinking", "quick-witted", "quickness",
  "quiet", "quintessential", "quip", "quirky", "quixotic", "quotable", "quotes",
  "racy", "rad", "radiant", "rainbow", "raise", "rapture", "rapturous",
  "rational", "ray of sunshine", "razor sharp", "razor-sharp", "ready",
  "reaffirmation", "real", "realistic", "realization", "reasonable",
  "reassuring", "rebellious", "recent posts", "recherche", "reclaim",
  "recommendable", "refined", "reflect", "reflective", "refreshing",
  "refulgent", "regain", "regal", "rehabilitate", "reinforce", "rejoice",
  "rejuvenate", "rejuvenated", "relationship", "relax", "relaxed", "relaxing",
  "relevant", "reliable", "relieved", "relinquish", "remarkable", "renew",
  "repair", "replace", "resilience", "resilient", "resolute", "resolve",
  "resourceful", "respect", "respectable", "respected", "respectful",
  "responsible", "restorative", "restore", "result", "resultant", "retain",
  "reverent", "revitalize", "revive", "revolutionary", "reward", "rewarding",
  "ribbons", "rich", "righteous", "robust", "romance", "romantic", 
  "rose colored", "rose-colored", "rosy", "saccharine", "safe", "sagacious",
  "sanctify", "sane", "sanitary", "sapid", "sassy", "satisfaction", "satisfied",
  "satisfying", "savior", "savor", "savvy", "scholar", "scholarly",
  "scrumptious", "secure", "seductive", "self assured", "self care", 
  "self consciousness", "self determination", "self disciplined", "self-assured",
  "self-care", "self-consciousness", "self-determination", "self-disciplined",
  "selfless", "sensational", "sense of humor", "sensible", "sensitive",
  "sensuous", "sentimental", "september", "serendipity", "serene", "serenity",
  "serviceable", "sexy", "sharing", "sharp", "shine", "significant", "sincere",
  "skilful", "skilled", "smart", "smashing", "smile", "smiling", "smooth",
  "snappy", "snazzy", "sociable", "solid", "sophisticated", "spark", "sparkle",
  "sparkling", "special", "spectacular", "spellbinding", "spiritual",
  "splendid", "splendiferous", "spontaneous", "sporty", "spunky", "stable",
  "star", "stellar", "striking", "strong", "stunning", "stupendous", "stylish",
  "success", "successful", "sunny", "super", "superb", "supportive", "surprise",
  "surprised", "survivor", "sustain", "sustainability", "sustainable", "sweet",
  "swift", "sympathetic", "sympathy", "tacos", "tactful", "tailor made",
  "tailor-made", "tailored", "talent", "talented", "tang", "tangible",
  "tasteful", "tasty", "teacher", "team", "teamwork", "teeming", "teens",
  "telegenic", "tenacious", "tenacity", "tenaciuos", "tender", "tender hearted",
  "tender-hearted", "tenderhearted", "terrific", "thankful", "thanks",
  "therapeutic", "therapy", "thinker", "thorough", "thoughtful", "thrift",
  "thrilling", "thrills", "thrive", "thriving", "time saving", "time-saving",
  "timeless", "timely", "tireless", "together", "togetherness", "tolerant", 
  "top quality", "top-quality", "traditional", "trailblazing", "tranquil",
  "transcendental", "transform", "treasure", "triumph", "true", "trust",
  "trusting", "trustworthy", "truth", "truthful", "tubular", "tusting",
  "ultimate", "unabashed", "unafraid", "unbeatable", "unbelievable", "unbiased",
  "unbound", "unbreakable", "unclouded", "unconditional", "unconventional",
  "uncorrupted", "undamaged", "understandable", "understanding",
  "unforgettable", "unify", "uninhabited", "unique", "united", "unselfish",
  "unshaken", "unspoiled", "unstoppable", "unsurpassed", "unwavering", "upbeat",
  "uplift", "uplifting", "upright", "upstanding", "urbane", "useful", "utile",
  "utilize", "utmost", "utopia", "utopian", "uttermost", "valiant", "validate",
  "valor", "valorous", "valuable", "value", "valued", "vanguardist", "vast",
  "vegetables", "vehement", "venerable", "venerate", "venture", "veracious",
  "verdurous", "verify", "veritable", "versatile", "versatility", "versed",
  "verve", "veteran", "viable", "vibrant", "victorious", "victory", "vigilant",
  "vigor", "vigorous", "vip", "virtue", "virtuous", "visionary", "vivacious",
  "vivid", "wanted", "warm", "warmly", "watchable", "watchful", "wealth",
  "wealthy", "weighty", "welcome", "welcoming", "well", "well advised", 
  "well balanced", "well behaved", "well done", "well educated", "well intentioned",
  "well liked", "well made", "well mannered", "well read", "well rounded", 
  "well suited", "well-advised", "well-balanced", "well-behaved", "well-done",
  "well-educated", "well-intentioned", "well-liked", "well-made",
  "well-mannered", "well-read", "well-rounded", "well-suited", "whacky",
  "wheeze", "whimsical", "whimsically", "whimsy", "whiz bang", "whiz-bang",
  "whole", "wholehearted", "wholeheartedly", "wholesome", "whopper", "willing",
  "willpower", "winner", "winsome", "wisdom", "wise", "witty", "wizardly",
  "wonder-filled", "wonderful", "wondrous", "worldly", "worthy", "wow", "xalam",
  "xanadu", "xanthocomic", "xanthophyll", "xebec", "xenial", "xenium",
  "xenization", "xenocracy", "xenocrat", "xenodochial", "xenodochium",
  "xenodochy", "xenoglossy", "xenolalia", "xenology", "xenomania", "xenophile",
  "xeriscape", "xerox", "xoxo", "yahoo", "yare", "yay", "yeah", "yearn",
  "yeehaw", "yeet", "yellow", "yep", "yes", "yield", "yip", "yippee", "yoga",
  "yogi", "yolo", "young", "yours", "yourself", "youth", "youthful", "yum",
  "yummiest", "yummy", "yummylicious", "yup", "yuppie", "zany", "zappy",
  "zazzy", "zeal", "zealful", "zealous", "zealousness", "zen", "zenned",
  "zephyr", "zest", "zestful", "zesty", "zing", "zinger", "zingy", "zipless",
  "zippier", "zippy", "zooty"
);