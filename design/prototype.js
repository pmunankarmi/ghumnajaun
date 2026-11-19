(() => {
const root = document.getElementById('gj');
const themeRoot = document.documentElement;
const themeToggle = document.getElementById('gj-theme-toggle');
const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');
function activeTheme(){
 return themeRoot.dataset.theme || (systemTheme.matches ? 'dark' : 'light');
}
function updateThemeToggle(){
 const nextTheme = activeTheme() === 'dark' ? 'light' : 'dark';
 themeToggle.dataset.nextTheme = nextTheme;
 themeToggle.setAttribute('aria-label','Switch to ' + nextTheme + ' mode');
 themeToggle.title = 'Switch to ' + nextTheme + ' mode';
}
themeToggle.addEventListener('click', () => {
 const theme = activeTheme() === 'dark' ? 'light' : 'dark';
 themeRoot.dataset.theme = theme;
 try { window.localStorage.setItem('ghumna-jaun-theme',theme); } catch {}
 updateThemeToggle();
});
systemTheme.addEventListener('change',updateThemeToggle);
updateThemeToggle();
themeToggle.hidden = false;

const photos = {
  "Nil Barahi Temple": {
    "src": "photos/nil-barahi-temple.webp",
    "alt": "Brick buildings and steps on the wooded grounds of Nil Barahi Temple",
    "author": "Bhaktapur.com",
    "source": "https://www.bhaktapur.com/discover/nil-barahi-temple/"
  },
  "Sangdaha Picnic Spot": {
    "src": "photos/sangdaha-picnic-spot.webp",
    "alt": "Roofed picnic shelter in the woods at Sangdaha Picnic Spot",
    "author": "Changunarayan Municipality tourism inventory",
    "source": "https://changu-stag.naxa.com.np/media/publication_files/37_Tourism_App_CHANGU_Final_Report.pdf#page=38"
  },
  "Kathmandu Fun Valley": {
    "src": "photos/kathmandu-fun-valley.webp",
    "alt": "Wave pool and waterslides at Kathmandu Fun Valley in Palanse",
    "author": "Kathmandu Fun Valley",
    "source": "https://kathmandufunvalley.fun/"
  },
  "Mahalaxmi Temple, Telkot": {
    "src": "photos/mahalaxmi-temple-telkot.webp",
    "alt": "Shree Pancha Laxmi temple grounds on a hillside near Telkot",
    "author": "Bhaktapur Dot Info",
    "source": "https://bhaktapur.info/shree-pancha-laxmi-mandir/"
  },
  "Ashapuri Mahadev Temple": {
    "src": "photos/ashapuri-mahadev-temple.webp",
    "alt": "Gold-roofed shrine at Ashapuri Mahadev Temple in Suryabinayak",
    "author": "Suryabinayak Municipality tourism profile",
    "source": "https://suryabinayaktourism.digitalmun.com/sites/aashapuri-mahadev-temple/"
  },
  "Jump KTM, Jhamsikhel": {
    "src": "photos/jump-ktm-jhamsikhel.webp",
    "alt": "Jump Jhamsikhel sign inside the indoor trampoline park",
    "author": "KhojaMandu venue gallery",
    "source": "https://khojamandu.com/places/jump-jhamsikhel-indoor-trampoline-park"
  },
  "Simulation Science Center": {
    "src": "photos/simulation-science-center.webp",
    "alt": "Rocket model outside the Simulation Science Center in Chobhar",
    "author": "Trip.com attraction gallery",
    "source": "https://au.trip.com/travel-guide/attraction/kirtipur/simulation-science-center-137371382/"
  },
  "Whoopee Land": {
    "src": "photos/whoopee-land.webp",
    "alt": "Colorful waterslides at Whoopee Land in Chobhar",
    "author": "Film Development Board, Nepal",
    "source": "https://film.gov.np/destination/17"
  },
  "Chonga Ganesh Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/16/Chonga_Ganesh_Temple.jpg",
    "alt": "Chonga Ganesh Temple in Sallaghari, Bhaktapur",
    "author": "PlutoBabyy",
    "commons": "https://commons.wikimedia.org/wiki/File:Chonga_Ganesh_Temple.jpg",
    "license": "4.0"
  },
  "Bhaktapur Durbar Square Panorama": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/14/Bhaktapur_Durbar_Square_360Degree_Panorama.jpg",
    "alt": "Bhaktapur Durbar Square panorama",
    "author": "Roshan Raj Adhikari",
    "commons": "https://commons.wikimedia.org/wiki/File:Bhaktapur_Durbar_Square_360Degree_Panorama.jpg",
    "license": "4.0"
  },
  "Bhaktapur Durbar Square Palace View": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/a6/Bhaktapur_Durbar_Square-_Nepal.jpg",
    "alt": "The 55-Window Palace and royal square in Bhaktapur",
    "author": "Safalphotos",
    "commons": "https://commons.wikimedia.org/wiki/File:Bhaktapur_Durbar_Square-_Nepal.jpg",
    "license": "4.0"
  },
  "Bhaktapur Golden Gate": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/7/7a/Golden_Gate-Bhaktapur_Durbar_Square_Premises_%281%29.jpg",
    "alt": "Gilded carvings above the Golden Gate in Bhaktapur Durbar Square",
    "author": "Nabin K. Sapkota",
    "commons": "https://commons.wikimedia.org/wiki/File:Golden_Gate-Bhaktapur_Durbar_Square_Premises_%281%29.jpg",
    "license": "4.0"
  },
  "Nyatapola Temple, Taumadhi": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/bc/Nyatapola_Temple%2C_Taumadhi_Square%2C_Bhaktapur%2C_Nepal_%28September_2026%29.jpg",
    "alt": "Nyatapola Temple in Taumadhi Square, Bhaktapur",
    "author": "Govind Rai / Getlostforever",
    "commons": "https://commons.wikimedia.org/wiki/File:Nyatapola_Temple%2C_Taumadhi_Square%2C_Bhaktapur%2C_Nepal_%28September_2026%29.jpg",
    "license": "BY"
  },
  "Pottery Square, Bhaktapur": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/ff/Pottery_square%2C_Bhaktapur.JPG",
    "alt": "Pottery Square in Bhaktapur",
    "author": "Vitbaisa",
    "commons": "https://commons.wikimedia.org/wiki/File:Pottery_square%2C_Bhaktapur.JPG",
    "license": "4.0"
  },
  "Nagarkot View Tower": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/e/e0/Nagarkot_View_Tower.jpg",
    "alt": "Nagarkot View Tower",
    "author": "Saroj Pandey",
    "commons": "https://commons.wikimedia.org/wiki/File:Nagarkot_View_Tower.jpg",
    "license": "3.0"
  },
  "Changu Narayan Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/3/32/Changu_narayan_temple.jpg",
    "alt": "Changu Narayan Temple in Bhaktapur",
    "author": "Prajan11",
    "commons": "https://commons.wikimedia.org/wiki/File:Changu_narayan_temple.jpg",
    "license": "4.0"
  },
  "Pharping": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/18/Pharping-Vajrayogini-12-Aussicht-2007-gje.jpg",
    "alt": "View from Vajrayogini Temple in Pharping",
    "author": "Gerd Eichmann",
    "commons": "https://commons.wikimedia.org/wiki/File:Pharping-Vajrayogini-12-Aussicht-2007-gje.jpg",
    "license": "4.0"
  },
  "Kirtipur": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/4/47/Historic_city_of_Kirtipur.jpg",
    "alt": "Historic Kirtipur",
    "author": "Sakar010",
    "commons": "https://commons.wikimedia.org/wiki/File:Historic_city_of_Kirtipur.jpg",
    "license": "4.0"
  },
  "Chandeshwori Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/0/06/Chandeshwori_Temple_10.jpg",
    "alt": "Chandeshwori Temple in Banepa",
    "author": "Prabina Karki",
    "commons": "https://commons.wikimedia.org/wiki/File:Chandeshwori_Temple_10.jpg",
    "license": "4.0"
  },
  "Chandeshwori Temple Courtyard": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/d/de/Chandeshwori_Banepa.JPG",
    "alt": "Chandeshwori Temple and its courtyard in Banepa",
    "author": "Krish Dulal",
    "commons": "https://commons.wikimedia.org/wiki/File:Chandeshwori_Banepa.JPG",
    "license": "3.0"
  },
  "Chandeshwori Temple Aerial View": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/96/Arial_View_of_Chandeswori_Temple.JPG",
    "alt": "Aerial view of the Chandeshwori Temple area in Banepa against a wooded hillside",
    "author": "Skrissh2013",
    "commons": "https://commons.wikimedia.org/wiki/File:Arial_View_of_Chandeswori_Temple.JPG",
    "license": "3.0"
  },
  "White Gumba": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/a8/White_-Seto_Gumba.jpg",
    "alt": "Seto Gumba in Nagarjun",
    "author": "Pradhan prarthana",
    "commons": "https://commons.wikimedia.org/wiki/File:White_-Seto_Gumba.jpg",
    "license": "4.0"
  },
  "Tribhuvan Park": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/6/60/%E0%A4%A4%E0%A5%8D%E0%A4%B0%E0%A4%BF%E0%A4%AD%E0%A5%81%E0%A4%B5%E0%A4%A8_%E0%A4%AA%E0%A4%BE%E0%A4%B0%E0%A5%8D%E0%A4%95_03.jpg",
    "alt": "Tribhuvan Park in Thankot",
    "author": "Punya",
    "commons": "https://commons.wikimedia.org/wiki/File:%E0%A4%A4%E0%A5%8D%E0%A4%B0%E0%A4%BF%E0%A4%AD%E0%A5%81%E0%A4%B5%E0%A4%A8_%E0%A4%AA%E0%A4%BE%E0%A4%B0%E0%A5%8D%E0%A4%95_03.jpg",
    "license": "4.0"
  },
  "Swayambhunath": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/a9/Swayambhunath_Stupa_by_day%2C_Kathmandu%2C_Nepal.jpg",
    "alt": "Swayambhunath stupa in Kathmandu",
    "author": "Vyacheslav Argenberg",
    "commons": "https://commons.wikimedia.org/wiki/File:Swayambhunath_Stupa_by_day%2C_Kathmandu%2C_Nepal.jpg",
    "license": "BY"
  },
  "Bajrayogini Temple View": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/2/2f/2015.07.16_Sankhu_94_Bajrayogini_Temple_View.jpg",
    "alt": "View at Bajrayogini Temple above Sankhu",
    "author": "Drdonnayates",
    "commons": "https://commons.wikimedia.org/wiki/File:2015.07.16_Sankhu_94_Bajrayogini_Temple_View.jpg",
    "license": "4.0"
  },
  "Bhrikutimandap Fun Park View": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/ab/Full_view_bhrikuti_mandap.JPG",
    "alt": "View across Kathmandu Fun Park at Bhrikutimandap",
    "author": "Sadmadd",
    "commons": "https://commons.wikimedia.org/wiki/File:Full_view_bhrikuti_mandap.JPG",
    "license": "3.0"
  },
  "Kathmandu Fun Park": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/9e/Bhrikuti_mandap_fun_park.JPG",
    "alt": "Ferris wheel at Kathmandu Fun Park in Bhrikutimandap",
    "author": "Sadmadd",
    "commons": "https://commons.wikimedia.org/wiki/File:Bhrikuti_mandap_fun_park.JPG",
    "license": "3.0"
  },
  "Natural History Museum": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/fc/Interior_of_Natural_History_Museum%2C_Kathmandu.jpg",
    "alt": "Exhibits inside the Natural History Museum in Swayambhu",
    "author": "Anup Sadi",
    "commons": "https://commons.wikimedia.org/wiki/File:Interior_of_Natural_History_Museum%2C_Kathmandu.jpg",
    "license": "4.0"
  },
  "Dakshinkali Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/5/55/Dakshinkali_Temple.JPG",
    "alt": "Dakshinkali Temple in Nepal",
    "author": "Shree Krishna Dhital",
    "commons": "https://commons.wikimedia.org/wiki/File:Dakshinkali_Temple.JPG",
    "license": "3.0"
  },
  "Four Binayak Ganesh Temples": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/b5/Chobhar-Jal_Binayak-16-Glaeubige-2007-gje.jpg",
    "alt": "Devotees at Jal Binayak Temple in Chobhar",
    "author": "Gerd Eichmann",
    "commons": "https://commons.wikimedia.org/wiki/File:Chobhar-Jal_Binayak-16-Glaeubige-2007-gje.jpg",
    "license": "4.0"
  },
  "Nagarkot": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/d/d8/Nagarkot_above_the_clouds_view_1.jpg",
    "alt": "Morning clouds over the hills at Nagarkot",
    "author": "Fpoylo",
    "commons": "https://commons.wikimedia.org/wiki/File:Nagarkot_above_the_clouds_view_1.jpg",
    "license": "4.0"
  },
  "Panauti": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/c/cb/Indreshwor_Temple%2C_Panauti.jpg",
    "alt": "Indreshwor Temple in Panauti",
    "author": "Creepanta",
    "commons": "https://commons.wikimedia.org/wiki/File:Indreshwor_Temple%2C_Panauti.jpg",
    "license": "4.0"
  },
  "Sankhu & Bajrayogini": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/d/d0/Bajrayogini_Temple_in_Sankhu_Town.jpg",
    "alt": "Bajrayogini Temple in Sankhu",
    "author": "Shahriar Amin Fahim247",
    "commons": "https://commons.wikimedia.org/wiki/File:Bajrayogini_Temple_in_Sankhu_Town.jpg",
    "license": "4.0"
  },
  "Chitlang": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/5/51/Beautiful_chitlang_village.jpg",
    "alt": "Landscape in Chitlang village",
    "author": "Roman Raghu Shrestha",
    "commons": "https://commons.wikimedia.org/wiki/File:Beautiful_chitlang_village.jpg",
    "license": "4.0"
  },
  "Kakani": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/3/33/Kakani.jpg",
    "alt": "View from Kakani",
    "author": "Brian Dell",
    "commons": "https://commons.wikimedia.org/wiki/File:Kakani.jpg",
    "license": "PD"
  },
  "Dhulikhel": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/f1/View_of_Mountain_Range_from_Dhulikhel.jpg",
    "alt": "Mountain ranges from Dhulikhel",
    "author": "Prabesh.poudel123",
    "commons": "https://commons.wikimedia.org/wiki/File:View_of_Mountain_Range_from_Dhulikhel.jpg",
    "license": "4.0"
  },
  "Banepa": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/4/41/Chandeshwori_Temple%2C_Banepa%2C_Nepal.jpg",
    "alt": "Chandeshwori Temple in Banepa",
    "author": "Algosig",
    "commons": "https://commons.wikimedia.org/wiki/File:Chandeshwori_Temple%2C_Banepa%2C_Nepal.jpg",
    "license": "3.0"
  },
  "Budhanilkantha": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/c/c5/NP-budhanilkantha-vishnu.jpg",
    "alt": "The sleeping Vishnu at Budhanilkantha",
    "author": "Bgabel",
    "commons": "https://commons.wikimedia.org/wiki/File:NP-budhanilkantha-vishnu.jpg",
    "license": "3.0"
  },
  "Dining": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/6/63/Nepali_dal_bhat_thali%2C_Kathmandu%2C_Nepal_%28September_2026%29.jpg",
    "alt": "Nepali dal bhat thali, photographed in Kathmandu",
    "author": "Govind Rai / Getlostforever",
    "commons": "https://commons.wikimedia.org/wiki/File:Nepali_dal_bhat_thali%2C_Kathmandu%2C_Nepal_%28September_2026%29.jpg",
    "license": "BY"
  },
  "Namo Buddha": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/2/27/Thrangu_Tashi_Yangtse_Monastery.jpg",
    "alt": "Thrangu Tashi Yangtse Monastery at Namo Buddha",
    "author": "Brian Dell",
    "commons": "https://commons.wikimedia.org/wiki/File:Thrangu_Tashi_Yangtse_Monastery.jpg",
    "license": "PD"
  },
  "Chandragiri Hills": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/ff/Chandragiri_Himalayan_range_view.jpg",
    "alt": "Himalayan range seen from Chandragiri",
    "author": "Tejan08",
    "commons": "https://commons.wikimedia.org/wiki/File:Chandragiri_Himalayan_range_view.jpg",
    "license": "4.0"
  },
  "Godawari Garden View": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/9d/Godawari%2C_Lalitpur.jpg",
    "alt": "Inside Godawari Botanical Garden",
    "author": "Shadow Ayush",
    "commons": "https://commons.wikimedia.org/wiki/File:Godawari%2C_Lalitpur.jpg",
    "license": "4.0"
  },
  "Godawari Hillside": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/3/34/Godawari_Park_Lalitpur.jpg",
    "alt": "View from the hills above Godawari",
    "author": "Abhishek69088",
    "commons": "https://commons.wikimedia.org/wiki/File:Godawari_Park_Lalitpur.jpg",
    "license": "4.0"
  },
  "Godawari Garden Stream": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/18/Stream_in_the_Godawari_Botanical_Garden.jpg",
    "alt": "Stream at Godawari Botanical Garden",
    "author": "Saroj Pandey",
    "commons": "https://commons.wikimedia.org/wiki/File:Stream_in_the_Godawari_Botanical_Garden.jpg",
    "license": "3.0"
  },
  "Godawari Fountain Pond": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/2/23/Godawari_botanical_garden_20180912_114846.jpg",
    "alt": "Fountain Pond at Godawari Botanical Garden",
    "author": "Ricky Partel",
    "commons": "https://commons.wikimedia.org/wiki/File:Godawari_botanical_garden_20180912_114846.jpg",
    "license": "4.0"
  },
  "Godawari Botanical Garden": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/ad/Godawari_Botanical_Garden_%2890%29.jpg",
    "alt": "Godawari Botanical Garden in Nepal",
    "author": "Nabin K. Sapkota",
    "commons": "https://commons.wikimedia.org/wiki/File:Godawari_Botanical_Garden_%2890%29.jpg",
    "license": "4.0"
  },
  "Bhaktapur Durbar Square": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/12/Bhaktapur_Durbar_square.jpg",
    "alt": "Bhaktapur Durbar Square",
    "author": "Suzalgurungz",
    "commons": "https://commons.wikimedia.org/wiki/File:Bhaktapur_Durbar_square.jpg",
    "license": "4.0"
  },
  "Kulekhani": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/c/cf/Indrasarowar_Kulekhani_Markhu.jpg",
    "alt": "Indra Sarowar reservoir at Markhu, Kulekhani",
    "author": "Pratap Baniya",
    "commons": "https://commons.wikimedia.org/wiki/File:Indrasarowar_Kulekhani_Markhu.jpg",
    "license": "4.0"
  },
  "Bheda Farm": {
    "src": "https://www.chitlang.com/wp-content/uploads/2024/08/Chitlang-Bheda-Farm.webp",
    "alt": "Bheda Farm near Chitlang",
    "author": "Chitlang Organic Village Resort",
    "source": "https://www.chitlang.com/bheda-farm-chitlang/"
  },
  "Kathmandu Durbar Square": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/d/dd/Kathmandu_Durbar_Square%2C_Kathmandu%2C_Nepal.jpg",
    "alt": "Kathmandu Durbar Square",
    "author": "Bikash Darai",
    "commons": "https://commons.wikimedia.org/wiki/File:Kathmandu_Durbar_Square%2C_Kathmandu%2C_Nepal.jpg",
    "license": "4.0"
  },
  "Patan Durbar Square": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/98/Patan_Durbar_Square%2C_Kathmandu.jpg",
    "alt": "Patan Durbar Square",
    "author": "Mukesh Joshi",
    "commons": "https://commons.wikimedia.org/wiki/File:Patan_Durbar_Square%2C_Kathmandu.jpg",
    "license": "3.0"
  },
  "Shikali Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/b4/Sikali_Temple.jpg",
    "alt": "Shikali Temple in Khokana",
    "author": "Setoghoda",
    "commons": "https://commons.wikimedia.org/wiki/File:Sikali_Temple.jpg",
    "license": "4.0"
  },
  "Rudrayani Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/ae/Rudrayani_Temple_Khokana_Lalitpur_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "alt": "Rudrayani Temple in Khokana",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Rudrayani_Temple_Khokana_Lalitpur_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "license": "4.0"
  },
  "Karya Binayak Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/9f/KaryaBinayak_Temple.jpg",
    "alt": "Karya Binayak Temple in Bungamati",
    "author": "Setoghoda",
    "commons": "https://commons.wikimedia.org/wiki/File:KaryaBinayak_Temple.jpg",
    "license": "4.0"
  },
  "Rato Machhindranath Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/a7/Rato_Machhindranath_Temple_Bungmati_Lalitpur_Nepal_Rajesh_Dhjungana_%287%29.jpg",
    "alt": "Rato Machhindranath Temple in Bungamati",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Rato_Machhindranath_Temple_Bungmati_Lalitpur_Nepal_Rajesh_Dhjungana_%287%29.jpg",
    "license": "4.0"
  },
  "Bajra Barahi Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/8/84/Bajrabarahi_Temple_Chapagaun_Jharuwabasi_Godawari_Municipility_Lalitpur_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "alt": "Bajra Barahi Temple in Chapagaon",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Bajrabarahi_Temple_Chapagaun_Jharuwabasi_Godawari_Municipility_Lalitpur_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "license": "4.0"
  },
  "Matsya Narayan Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/d/d0/Matsyanarayan_Temple_at_Kirtipur.jpg",
    "alt": "Matsya Narayan Temple in Machhegaun",
    "author": "Mohan K. Duwal",
    "commons": "https://commons.wikimedia.org/wiki/File:Matsyanarayan_Temple_at_Kirtipur.jpg",
    "license": "4.0"
  },
  "Doleshwor Mahadev Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/b4/Doleshwor_Mahadev_Temple_01.jpg",
    "alt": "Doleshwor Mahadev Temple in Sipadol, Bhaktapur",
    "author": "Prabina Karki",
    "commons": "https://commons.wikimedia.org/wiki/File:Doleshwor_Mahadev_Temple_01.jpg",
    "license": "4.0"
  },
  "Doleshwor Mahadev Temple Courtyard": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/9f/Doleshwar_Temple.jpg",
    "alt": "Doleshwor Mahadev Temple courtyard with a Nandi statue and the wooded hillside behind it",
    "author": "Ganesh Niraula",
    "commons": "https://commons.wikimedia.org/wiki/File:Doleshwar_Temple.jpg",
    "license": "BY"
  },
  "Doleshwor Mahadev Temple Nandi": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/ba/Doleshwor_Mahadev_Temple_06.jpg",
    "alt": "Gold-colored Nandi statue at Doleshwor Mahadev Temple in Sipadol",
    "author": "Prabina Karki",
    "commons": "https://commons.wikimedia.org/wiki/File:Doleshwor_Mahadev_Temple_06.jpg",
    "license": "4.0"
  },
  "Ichangu Narayan Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/b/bf/DSC_0375_Ichangu_Narayan_Temple.jpg",
    "alt": "Ichangu Narayan Temple in Ichangu, Nagarjun",
    "author": "Sumanneupane011",
    "commons": "https://commons.wikimedia.org/wiki/File:DSC_0375_Ichangu_Narayan_Temple.jpg",
    "license": "4.0"
  },
  "1,000 Steps, Dhulikhel": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/1c/Kalidevi_Temple%E2%80%93Dhulikhel_%E2%80%93_01.jpg",
    "alt": "Kalidevi Temple above Dhulikhel",
    "author": "Maesi64",
    "commons": "https://commons.wikimedia.org/wiki/File:Kalidevi_Temple%E2%80%93Dhulikhel_%E2%80%93_01.jpg",
    "license": "PD"
  },
  "Central Zoo": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/ff/Central_zoo%2C_Jawalakhel6.jpg",
    "alt": "Gharials at the Central Zoo in Jawalakhel",
    "author": "Shadow Ayush",
    "commons": "https://commons.wikimedia.org/wiki/File:Central_zoo%2C_Jawalakhel6.jpg",
    "license": "4.0"
  },
  "National Museum of Nepal": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/7/74/National_Museum%2C_Kathmandu%2C_Nepal.JPG",
    "alt": "National Museum of Nepal in Chhauni",
    "author": "Shoestring",
    "commons": "https://commons.wikimedia.org/wiki/File:National_Museum%2C_Kathmandu%2C_Nepal.JPG",
    "license": "4.0"
  },
  "Adinath Lokeshwar Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/8/88/Adinath_Temple_Aadi_Nath_Temple_Chobhar_Dada_Kirtipur_Kathmandu_Nepal_Rajesh_Dhungana_1.jpg",
    "alt": "Adinath Lokeshwar Temple in Chobhar",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Adinath_Temple_Aadi_Nath_Temple_Chobhar_Dada_Kirtipur_Kathmandu_Nepal_Rajesh_Dhungana_1.jpg",
    "license": "4.0"
  },
  "Bagh Bhairab Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/ab/Bagh_Bhairab_Temple.jpg",
    "alt": "Bagh Bhairab Temple in Kirtipur",
    "author": "Janak Bhatta",
    "commons": "https://commons.wikimedia.org/wiki/File:Bagh_Bhairab_Temple.jpg",
    "license": "4.0"
  },
  "Indrayani Temple (Luti Ajima)": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/1/19/Ajima_Indrayani_Lu_ti_Azim_Kathmandu%2C_Nepal_Rajesh_Dhungana.jpg",
    "alt": "Indrayani Temple at Luti Ajima in Kathmandu",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Ajima_Indrayani_Lu_ti_Azim_Kathmandu%2C_Nepal_Rajesh_Dhungana.jpg",
    "license": "4.0"
  },
  "Mhepi Ajima Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/2/25/Mahepi_Ajima_Temple_Mahepi_Kathmandu_Nepal_Rajesh_Dhungana_%2821%29.jpg",
    "alt": "Mhepi Ajima Temple in Kathmandu",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Mahepi_Ajima_Temple_Mahepi_Kathmandu_Nepal_Rajesh_Dhungana_%2821%29.jpg",
    "license": "4.0"
  },
  "Bijeshwari Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/9/9d/Bijeshwari_Bajra_Yogini_Temple_Rajesh_Dhungana_Kathmandu_%283%29.jpg",
    "alt": "Bijeshwari Temple in Kathmandu",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Bijeshwari_Bajra_Yogini_Temple_Rajesh_Dhungana_Kathmandu_%283%29.jpg",
    "license": "4.0"
  },
  "Santaneshwor Mahadev Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/f/fa/Shree_Santaneshwor_Mahadev_Temple_20180828_152945.jpg",
    "alt": "Santaneshwor Mahadev Temple in Jharuwarasi",
    "author": "Ricky Partel",
    "commons": "https://commons.wikimedia.org/wiki/File:Shree_Santaneshwor_Mahadev_Temple_20180828_152945.jpg",
    "license": "4.0"
  },
  "Balaju Baisdhara Park": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/a/a5/Baishdhara_Pond_Holy_Pond_Balaju%2C_Kathmandu_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "alt": "Pond at Balaju Baisdhara Park",
    "author": "Rajesh Dhungana",
    "commons": "https://commons.wikimedia.org/wiki/File:Baishdhara_Pond_Holy_Pond_Balaju%2C_Kathmandu_Nepal_Rajesh_Dhungana_%281%29.jpg",
    "license": "4.0"
  },
  "Surya Binayak Temple": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/7/72/Suryavinayak_Temple10.jpg",
    "alt": "Surya Binayak Temple in Bhaktapur",
    "author": "Janak Bhatta",
    "commons": "https://commons.wikimedia.org/wiki/File:Suryavinayak_Temple10.jpg",
    "license": "4.0"
  },
  "Manjushree Park": {
    "src": "https://upload.wikimedia.org/wikipedia/commons/c/ce/Manjushree_Park_Khola_Chobhar.jpg",
    "alt": "Chobhar Gorge at Manjushree Park",
    "author": "Prabina Karki",
    "commons": "https://commons.wikimedia.org/wiki/File:Manjushree_Park_Khola_Chobhar.jpg",
    "license": "4.0"
  }
};
const picnicCategory = 'Picnic Spot';
const categories = ['Family','Couple','Friends','Group','Honeysun','Honeymoon','Dharmik',picnicCategory,'Kids'];
const places = [
 ["Kathmandu Fun Park","Amusement rides · Bhrikutimandap",'Kids|Family|Group'],
 ["Natural History Museum","Indoor museum · Swayambhu",'Kids|Family|Group'],
 ["Jump KTM, Jhamsikhel","Indoor trampolines · Jhamsikhel",'Kids|Family|Group'],
 ["Whoopee Land","Water and amusement park · Chobhar",'Kids|Family|Group'],
 ["Kathmandu Fun Valley","Water park · Palanse, Bhaktapur",'Kids|Family|Group'],
 ["Bagh Bhairab Temple","Temple · Kirtipur","Dharmik|Family|Group"],
 ["Indrayani Temple (Luti Ajima)","Temple · Kathmandu","Dharmik|Family|Group"],
 ["Mhepi Ajima Temple","Temple · Kathmandu","Dharmik|Family|Group"],
 ["Bijeshwari Temple","Temple · Kathmandu","Dharmik|Family|Group"],
 ["Santaneshwor Mahadev Temple","Temple · Jharuwarasi, Lalitpur","Dharmik|Family|Group"],
 ["Chonga Ganesh Temple","Temple & pine grove · Sallaghari, Bhaktapur","Dharmik|Family|Group|Picnic Spot"],
 ["Surya Binayak Temple","Temple & picnic area · Suryabinayak","Dharmik|Family|Group|Picnic Spot"],
 ["Tribhuvan Park","Picnic park · Thankot","Family|Friends|Group|Picnic Spot"],
 ["Balaju Baisdhara Park","Picnic park · Balaju, Kathmandu","Family|Friends|Group|Picnic Spot"],
 ["Manjushree Park","Picnic park · Chobhar","Family|Friends|Group|Picnic Spot|Kids"],
 ["Sangdaha Picnic Spot","Forest picnic grounds · Changunarayan","Family|Friends|Group|Picnic Spot"],
 ['1,000 Steps, Dhulikhel','Hill walk · Dhulikhel','Family|Friends|Group|Dharmik'],
 ['Central Zoo','Zoo · Jawalakhel','Family|Group|Kids'],
 ['Simulation Science Center','Science centre · Chobhar','Family|Group|Kids'],
 ['National Museum of Nepal','Museum · Chhauni','Family|Friends|Group|Kids'],
 ['Adinath Lokeshwar Temple','Temple · Chobhar','Dharmik|Family|Group'],
 ['Bajra Barahi Temple','Temple · Chapagaon','Dharmik|Family|Group','dharmik'],
 ['Nil Barahi Temple','Temple · Bode, Madhyapur Thimi','Dharmik|Family|Group','dharmik'],
 ['Chandeshwori Temple','Temple · Banepa','Dharmik|Family|Group','dharmik'],
 ['Matsya Narayan Temple','Temple · Machhegaun','Dharmik|Family|Group','dharmik'],
 ['Doleshwor Mahadev Temple','Temple · Sipadol, Bhaktapur','Dharmik|Family|Group','dharmik'],
 ['Ashapuri Mahadev Temple','Temple · Suryabinayak, Bhaktapur','Dharmik|Family|Group','dharmik'],
 ['Ichangu Narayan Temple','Temple · Ichangu, Nagarjun','Dharmik|Family|Group','dharmik'],
 ['Shikali Temple','Temple · Khokana','Dharmik|Family|Group|Picnic Spot','dharmik'],
 ['Rudrayani Temple','Temple · Khokana','Dharmik|Family|Group','dharmik'],
 ['Karya Binayak Temple','Temple · Bungamati','Dharmik|Family|Group','dharmik'],
 ['Rato Machhindranath Temple','Temple · Bungamati','Dharmik|Family|Group','dharmik'],
 ['Jal Binayak Temple','Temple · Chobhar','Dharmik|Family|Group','dharmik'],
 ['Nagarkot','Hill getaway','Family|Couple|Honeysun|Honeymoon'],
 ['Panauti','Heritage walk','Family|Friends|Group|Dharmik','heritage'],
 ['Sankhu & Bajrayogini','Heritage & pilgrimage','Family|Friends|Group|Dharmik|Picnic Spot','heritage'],
 ['Four Binayak Ganesh Temples','Pilgrimage circuit','Dharmik|Family|Group'],
 ['Chitlang','Village getaway','Family|Friends|Group|Couple'],
 ['Kulekhani','Lakeside getaway','Family|Friends|Group|Couple'],
 ['Bheda Farm','Countryside outing','Family|Friends|Group|Couple'],
 ['Mahalaxmi Temple, Telkot','Temple','Dharmik|Family','dharmik'],
 ['Kakani','Hill getaway','Family|Friends|Group'],
 ['Namo Buddha','Monastery outing','Dharmik|Family|Couple','dharmik'],
 ['Banepa','Town outing','Family|Friends','heritage'],
 ['Budhanilkantha','Temple','Dharmik|Family','dharmik'],
 ['White Gumba','Monastery','Dharmik','dharmik'],
 ['Kirtipur','Heritage and food','Friends|Family|Group','heritage'],
 ['Dhulikhel','Hill getaway','Family|Couple|Honeysun|Honeymoon'],
 ['Chandragiri Hills','Hill getaway','Family|Couple|Friends|Group|Kids'],
 ['Godawari Botanical Garden','Garden outing · Godawari','Family|Couple|Friends|Group|Picnic Spot|Kids'],
 ['Kathmandu Durbar Square','Heritage walk','Family|Friends|Group|Dharmik'],
 ['Patan Durbar Square','Heritage walk','Family|Friends|Group|Dharmik'],
 ['Bhaktapur Durbar Square','Heritage walk','Family|Friends|Group|Dharmik'],
 ['Pharping','Pilgrimage outing','Family|Dharmik|Group'],
 ['Dakshinkali Temple','Temple','Dharmik|Family|Group','dharmik'],
 ['Swayambhunath','Stupa','Family|Dharmik|Friends'],
 ['Changu Narayan Temple','Temple & nearby picnic grounds · Changunarayan','Family|Dharmik|Group|Picnic Spot'],
 ['Hillside terrace near Nagarkot','Scenic dining','Family|Couple|Honeysun','', {
   view: 'Hill and valley views', seating: 'Outdoor terrace', budget: 'NPR 700–1,100 per person',
   menu: ['Nepali lunch set · NPR 650', 'Vegetable momos · NPR 250', 'Milk tea · NPR 100']
 }],
 ['Garden restaurant near Dhulikhel','Scenic dining','Couple|Friends|Group','heritage', {
   view: 'Valley views', seating: 'Garden seating', budget: 'NPR 600–1,000 per person',
   menu: ['Chicken lunch set · NPR 600', 'Vegetable chow mein · NPR 300', 'Lemon tea · NPR 100']
 }]
];
const dayPlans = {
 'Shikali Temple': [
   'Morning · Visit Shikali Temple outside Khokana, then continue to Rudrayani Temple in the village',
   'Midday · Stop for lunch before heading to Bungamati',
   'Afternoon · Visit Karya Binayak and Rato Machhindranath Temple'
 ],
 'Dakshinkali Temple': [
   'Morning · Visit Dakshinkali Temple and allow time for darshan',
   'Midday · Stop for lunch in Pharping',
   'Afternoon · Explore Pharping or head back'
 ],
 'Four Binayak Ganesh Temples': [
   'Morning · Start at Ashok Binayak in Maru, then visit Chandra Binayak in Chabahil',
   'Midday · Visit Surya Binayak in Bhaktapur and stop for lunch',
   'Afternoon · Continue to Jal Binayak in Chobhar'
 ],
 'Kulekhani': ['Morning · Head to the reservoir at Markhu','Midday · Stop for lunch by the lake','Afternoon · Explore the lakeside before heading home'],
 'Bheda Farm': ['Morning · Start from Chitlang or Markhu','Midday · Explore the farm area on foot','Afternoon · Combine the outing with a stop at Kulekhani'],
 'Chandragiri Hills': ['Morning · Head to Chandragiri','Midday · Spend time at the viewpoint and stop for lunch','Afternoon · Leave time for the return journey'],
 'Godawari Botanical Garden': ['Morning · Explore the garden on foot','Midday · Stop for lunch nearby'],
 'Bhaktapur Durbar Square': ['Morning · Walk through Durbar Square','Midday · Stop for Newari food','Afternoon · Continue exploring the old town'],
 'Nagarkot': ['Morning · Start with a viewpoint visit','Midday · Choose a restaurant for lunch','Afternoon · Take a short walk, then return'],
 'Panauti': ['Morning · Explore the old town on foot','Midday · Visit Indreshwor Temple and stop for lunch','Afternoon · Allow time for a riverside walk'],
 'Sankhu & Bajrayogini': ['Morning · Walk through the historic town','Midday · Visit Bajrayogini Temple','Afternoon · Return to town for a meal before heading back'],
 'Chitlang': ['Morning · Explore the village on foot','Midday · Stop for lunch','Afternoon · Leave time for the return drive'],
 'Namo Buddha': ['Morning · Travel to Namo Buddha','Midday · Visit the monastery grounds','Afternoon · Pair the visit with Panauti if time allows']
};
// Reference points are approximate town centers or outing start points.
const origins = {
 'Kathmandu Valley': [27.717, 85.324],
 Kathmandu: [27.717, 85.324],
 Lalitpur: [27.673, 85.325],
 Bhaktapur: [27.672, 85.429]
};
const placePoints = {
 'Mahalaxmi Temple, Telkot': [27.7168, 85.4722],
 'White Gumba': [27.7257, 85.2612],
 "Kathmandu Fun Park": [27.70087, 85.31952],
 "Natural History Museum": [27.71457, 85.28783],
 "Jump KTM, Jhamsikhel": [27.6830355, 85.3101159],
 "Whoopee Land": [27.66176, 85.28649],
 "Kathmandu Fun Valley": [27.64713, 85.46717],
 'Simulation Science Center': [27.66784, 85.28649],
 "Bagh Bhairab Temple": [27.6794, 85.27647],
 "Indrayani Temple (Luti Ajima)": [27.7139625, 85.302515625],
 "Mhepi Ajima Temple": [27.72752, 85.30856],
 "Bijeshwari Temple": [27.71393, 85.30077],
 "Santaneshwor Mahadev Temple": [27.61526, 85.34419],
 "Chonga Ganesh Temple": [27.674293, 85.411025],
 "Surya Binayak Temple": [27.656277, 85.423521],
 "Tribhuvan Park": [27.69018, 85.22097],
 "Balaju Baisdhara Park": [27.73363, 85.30082],
 "Manjushree Park": [27.65972, 85.29345],
 "Sangdaha Picnic Spot": [27.713813, 85.425567],
 '1,000 Steps, Dhulikhel': [27.610491, 85.568159],
 'Central Zoo': [27.67319, 85.31072],
 'National Museum of Nepal': [27.70544, 85.28914],
 'Adinath Lokeshwar Temple': [27.66578, 85.2914],
 'Bajra Barahi Temple': [27.606106, 85.329349],
 'Nil Barahi Temple': [27.695441, 85.396436],
 'Chandeshwori Temple': [27.63583, 85.53222],
 'Matsya Narayan Temple': [27.66, 85.25],
 'Doleshwor Mahadev Temple': [27.63889, 85.43806],
 'Ashapuri Mahadev Temple': [27.618845, 85.460068],
 'Ichangu Narayan Temple': [27.72995, 85.26401],
 'Shikali Temple': [27.64339, 85.28543],
 'Rudrayani Temple': [27.63578, 85.2981],
 'Karya Binayak Temple': [27.6319, 85.30227],
 'Rato Machhindranath Temple': [27.62966, 85.30212],
 'Jal Binayak Temple': [27.65831, 85.29318],
 Nagarkot: [27.714, 85.521],
 Panauti: [27.585, 85.517],
 'Sankhu & Bajrayogini': [27.747, 85.483],
 Chitlang: [27.650, 85.170],
 Kulekhani: [27.615, 85.155],
 'Bheda Farm': [27.622, 85.152],
 Kakani: [27.814, 85.267],
 'Namo Buddha': [27.570, 85.581],
 Banepa: [27.629, 85.521],
 Budhanilkantha: [27.775, 85.362],
 Kirtipur: [27.678, 85.277],
 Dhulikhel: [27.621, 85.546],
 'Chandragiri Hills': [27.660, 85.203],
 'Godawari Botanical Garden': [27.596, 85.384],
 'Kathmandu Durbar Square': [27.704, 85.307],
 'Patan Durbar Square': [27.673, 85.325],
 'Bhaktapur Durbar Square': [27.673, 85.428],
 Pharping: [27.613, 85.265],
 'Dakshinkali Temple': [27.60526, 85.26349],
 Swayambhunath: [27.715, 85.290],
 'Changu Narayan Temple': [27.717, 85.427]
};
function distanceFrom(origin,point){
 if (!point) return Infinity;
 const radians = angle => angle * Math.PI / 180;
 const lat = radians(point[0] - origin[0]);
 const lon = radians(point[1] - origin[1]);
 const chord = Math.sin(lat / 2) ** 2 + Math.cos(radians(origin[0])) * Math.cos(radians(point[0])) * Math.sin(lon / 2) ** 2;
 return 6371 * 2 * Math.asin(Math.sqrt(Math.min(1,chord)));
}
const citySightseeing = {
 Kathmandu: ['Kathmandu Durbar Square','Swayambhunath','National Museum of Nepal','Kathmandu Fun Park','Natural History Museum','Balaju Baisdhara Park','Indrayani Temple (Luti Ajima)','Mhepi Ajima Temple','Bijeshwari Temple'],
 Lalitpur: ['Patan Durbar Square','Central Zoo','Jump KTM, Jhamsikhel'],
 Bhaktapur: ['Bhaktapur Durbar Square','Chonga Ganesh Temple'],
 Banepa: ['Banepa','Chandeshwori Temple'],
 Panauti: ['Panauti'],
 Dhulikhel: ['Dhulikhel','1,000 Steps, Dhulikhel']
};
const cityPlaces = name => places.filter(([placeName]) => citySightseeing[name].includes(placeName));
const familyTrips = ['Kathmandu Fun Park','Central Zoo','Natural History Museum'];
const entryFeeSource = 'https://ntb.gov.np/en/plan-your-trip/before-you-come/heritage-site-entry-fees';
const entryFees = {
 'Kathmandu Durbar Square': [500,1000],
 'Patan Durbar Square': [500,1000],
 'Bhaktapur Durbar Square': [500,1800],
 Swayambhunath: [50,200],
 'Changu Narayan Temple': [100,300]
};
const destinationGuides = {
 'Bheda Farm': {
   summary: "The open slopes near Chitlang and Markhu make this a short countryside stop, with pasture views and room to slow down between destinations. Pair it with a longer walk in Chitlang or lunch by Kulekhani Reservoir.",
   stops: [
     ["Bheda Farm, Chitlang", "Take in the open pasture views before continuing toward Markhu.", "Bheda Farm"],
     ["Kulekhani Reservoir, Markhu", "Pause in Markhu for a walk by the reservoir or a meal by the water.", "Kulekhani"]
   ],
   transport: 'Confirm the last stretch of the approach with your driver before leaving Chitlang or Markhu.',
   sources: [['Chitlang Organic Village Resort farm guide','https://www.chitlang.com/bheda-farm-chitlang/']]
 },
 'Mahalaxmi Temple, Telkot': {
   summary: "Shree Pancha Mahalaxmi is a hillside temple near Telkot on the Bhaktapur side of the Nagarkot hills. It works as a short worship stop on a day that also includes Telkot or a viewpoint farther up the ridge.",
   stops: [
     ["Shree Pancha Mahalaxmi Temple, Telkot, Bhaktapur", "Visit the hillside shrine before heading farther up the Nagarkot ridge.", "Mahalaxmi Temple, Telkot"],
     ["Telkot, Bhaktapur","Make a short stop in Telkot between the temple and the ridge viewpoints."]
   ],
   transport: 'Use the Pancha Mahalaxmi pin near Telkot and check the final turn before you leave the main road.',
   sources: [['Changunarayan municipal transport plan','https://changu-stag.naxa.com.np/media/publication_files/18.pdf']]
 },
 Kakani: {
   summary: "Kakani makes an easygoing hill outing beyond Kathmandu, with space for a short walk and views across the surrounding hills when skies are clear. Start at the viewpoint and leave time for the memorial park or lunch before the return drive.",
   stops: [
     ["Kakani Viewpoint, Nuwakot", "Look across the surrounding hills when the sky is clear.", "Kakani"],
     ["Kakani Memorial Park, Nuwakot","Take a short walk around the memorial grounds after the viewpoint."]
   ],
   bus: 'Ask for a passenger vehicle from Kathmandu toward Kakani. Confirm the boarding point, the closest drop-off to the viewpoint, and your return ride.',
   transport: 'Set your navigation to Kakani Viewpoint. Use a separate pin for the memorial park if you are visiting both.',
   source: 'https://trade.ntb.gov.np/tourist-destination/hill-stations/'
 },
 Banepa: {
   summary: "Banepa pairs a busy Kavre bazaar with quieter temple stops outside the centre. Walk through the market for a feel for the town, then make a separate stop at Chandeshwori Temple to the north.",
   history: ["Medieval palm-leaf records cited by Banepa Municipality refer to the settlement by earlier names, including Vinayapuri. The municipality also traces the town’s commercial roots to its long trading relationship with Tibet.", "Banepa Municipality’s history", "https://banepamun.gov.np/ne/node/3"],
   stops: [
     ["Banepa Bazaar, Kavre","Walk through the market streets before heading north to the temple."],
     ["Chandeshwori Temple, Banepa", "Pause in the courtyard to look at the tiered shrine and its carved details.", "Chandeshwori Temple Courtyard"]
   ],
   transport: 'Start near the town centre and use a separate pin for Chandeshwori Temple.',
   sources: [['Banepa Municipality map','https://banepamun.gov.np/sites/banepamun.gov.np/files/RoadMap1.pdf']]
 },
 Budhanilkantha: {
   summary: "The centrepiece of Budhanilkantha is a stone image of Vishnu reclining on a serpent in a temple pool. The courtyard is an active place of worship, so allow time for darshan and give devotees space around the shrine.",
   stops: [["Budhanilkantha Temple, Kathmandu", "See the stone Vishnu reclining on a serpent in the temple pool.", "Budhanilkantha"]],
   source: 'https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/'
 },
 'White Gumba': {
   summary: "White Gumba is the Druk Amitabha Mountain Buddhist campus in Nagarjun, home to a community of Drukpa nuns. The uphill visit is best planned around public access to the grounds, with time to take in the hillside setting.",
   stops: [["Druk Amitabha Mountain Monastery, Nagarjun", "Explore the hillside grounds when open to visitors and follow the monastery’s guidance.", "White Gumba"]],
   transport: 'Confirm public access before taking the uphill approach to the monastery.',
   sources: [['Druk Amitabha Mountain monastery','https://www.drukpa-nuns.org/']]
 },
 Kirtipur: {
   summary: "Kirtipur is a hilltop Newar town best explored on foot, from Bagh Bhairab Temple through the lanes toward Uma Maheshwar Temple. Leave time for a Newari meal and look out for views of the valley between the buildings.",
   history: ["Kirtipur was a fortified hill town in the Kathmandu Valley. It resisted Gorkha’s attempts to capture it before becoming part of Prithvi Narayan Shah’s expanding kingdom.", "Kirtipur Municipality’s account", "https://kirtipurmun.gov.np/history/the-fall-of-kirtipur-and-its-integration-into-the-gorkha-kingdom?lang=en"],
   stops: [
     ["Bagh Bhairab Temple, Kirtipur", "Look for the three-tiered Newar shrine dedicated to Bhairab in tiger form.", "Bagh Bhairab Temple"],
     ["Uma Maheshwar Temple, Kirtipur","Continue through the old lanes to the hilltop shrine for a view over the valley."]
   ],
   transport: 'Plan to explore the old town on foot and arrange a pickup point outside the narrow lanes.',
   sources: [['Kirtipur Municipality heritage guide','https://kirtipurmun.gov.np/heritage/baghbhairav/']]
 },
 Dhulikhel: {
   summary: "Dhulikhel offers two different ways to spend the day: a walk through the old town or a climb toward Kalidevi Temple. Start in the lanes and squares if you prefer a gentler outing, or build the day around the 1,000 Steps and the hilltop views.",
   stops: [
     ["Dhulikhel Old Town","Wander through the lanes and squares for a gentler outing before the hill climb."],
     ["1,000 Steps, Dhulikhel","Follow the stone steps uphill toward Kalidevi Temple, taking breaks as needed."]
   ],
   bus: 'Ask for a Dhulikhel-bound bus and confirm the boarding point and last return. Get off near the town centre for the old-town walk.',
   transport: 'Choose the old town or the lower end of the 1,000 Steps as your drop-off, depending on where you want to start.',
   source: 'https://trade.ntb.gov.np/tourist-destination/hill-stations/'
 },
 'Kathmandu Durbar Square': {
   summary: "Walk through Basantapur to see the Hanuman Dhoka palace precinct, tiered temples and carved windows around Kumari Ghar. The courtyards and squares sit in the middle of a working city, so the visit can extend into the surrounding lanes.",
   history: ["Hanuman Dhoka was a royal palace precinct. The palace, temples and public squares show how court life and worship shared the centre of historic Kathmandu. The precinct is one of seven monument zones in the Kathmandu Valley World Heritage site.", "UNESCO Kathmandu Valley record", "https://whc.unesco.org/en/list/121/"],
   stops: [
     ["Hanuman Dhoka Palace, Kathmandu","Explore the former royal palace precinct and its courtyards off Durbar Square."],
     ["Kumari Ghar, Basantapur","Look up at the carved wooden windows of the Kumari’s residence from the square."]
   ],
   sources: [['UNESCO Kathmandu Valley heritage guide','https://whc.unesco.org/en/list/121/']]
 },
 'Patan Durbar Square': {
   summary: "Patan’s royal square brings stone temples and brick palace courtyards together in the centre of Lalitpur. Spend time at Krishna Mandir and the Patan Museum to see Newar craftsmanship at street level and up close.",
   history: ["Patan’s Durbar Square grew around its royal palace and the temples and public spaces beside it. The ensemble preserves examples of the valley’s Newar architecture and craftsmanship and is part of the Kathmandu Valley World Heritage site.", "UNESCO Kathmandu Valley record", "https://whc.unesco.org/en/list/121/"],
   stops: [
     ["Patan Museum, Lalitpur","Step inside the former palace to see religious art and examples of Newar craftsmanship."],
     ["Krishna Mandir, Patan Durbar Square","Pause in the square to look at the stone temple and its carved tiers."]
   ],
   sources: [['UNESCO Kathmandu Valley heritage guide','https://whc.unesco.org/en/list/121/']]
 },
 'Bhaktapur Durbar Square': {
   summary: "Start among the palace buildings and carved façades of Durbar Square, then walk to Nyatapola in Taumadhi and on to Pottery Square. The route combines royal architecture with the everyday work and street life of old Bhaktapur.",
   history: ["Bhaktapur’s royal square was a centre of court and religious life. The palace, temples and squares preserve evidence of the city’s historic role and are included in the Kathmandu Valley World Heritage site.", "UNESCO Kathmandu Valley record", "https://whc.unesco.org/en/list/121/"],
   stops: [
     ["55 Window Palace, Bhaktapur", "Look up at the carved wooden windows along the palace façade, then see the Golden Gate beside it.", "Bhaktapur Durbar Square Palace View"],
     ["Nyatapola Temple, Taumadhi", "Take in the five-tiered temple from Taumadhi Square and look for the guardian figures lining its staircase.", "Nyatapola Temple, Taumadhi"],
     ["Pottery Square, Bhaktapur", "Look for potters shaping clay on traditional wheels and earthenware set out to dry.", "Pottery Square, Bhaktapur"]
   ],
   sources: [
     ['UNESCO Kathmandu Valley heritage guide','https://whc.unesco.org/en/list/121/'],
     ['Bhaktapur Municipality guide to the squares','https://www.bhaktapurmun.gov.np/en/node/4']
   ]
 },
 Pharping: {
   summary: "Pharping brings together Buddhist and Hindu pilgrimage sites on the southern side of the valley. Visit Asura Cave and Vajrayogini Temple as separate stops around town, then continue to Dakshinkali if you have time.",
   stops: [
     ["Vajrayogini Temple, Pharping","Make time for the Vajrayogini shrine before continuing to the cave."],
     ["Asura Cave, Pharping","Visit the Buddhist pilgrimage cave and give worshippers space in the small shrine area."],
     ["Dakshinkali Temple", "Allow time for darshan at the shrine dedicated to Kali.", "Dakshinkali Temple"]
   ],
   sources: [['Nepal Tourism Board Dakshinkali guide','https://ntb.gov.np/en/dakshinkali-']]
 },
 Swayambhunath: {
   summary: "Swayambhunath crowns a hill above Kathmandu with a white stupa, prayer wheels and smaller shrines around its base. Follow the circuit at your own pace and pause where the hill opens onto views across the city.",
   history: ["UNESCO identifies the Swayambhu stupa as the Kathmandu Valley’s oldest Buddhist monument. The stupa and the religious buildings around it make up one of the valley’s seven World Heritage monument zones.", "UNESCO Kathmandu Valley record", "https://whc.unesco.org/en/list/121/"],
   stops: [
     ["Swayambhunath Stupa, Kathmandu", "Walk around the hilltop stupa past prayer wheels and smaller shrines.", "Swayambhunath"],
     ["Natural History Museum, Swayambhu", "See preserved specimens of Nepal’s wildlife at the museum below the stupa.", "Natural History Museum"]
   ],
   transport: 'Check which entrance suits your group. The main stairway involves a steep climb.',
   sources: [['UNESCO Kathmandu Valley heritage guide','https://whc.unesco.org/en/list/121/']]
 },
 "Kathmandu Fun Park": {
   summary: "A compact amusement park in Bhrikutimandap, Kathmandu Fun Park is a central stop for families looking for a few rides and a snack break. Its location makes it easy to combine with a short walk around Ratna Park or a longer outing to Basantapur.",
   stops: ["Kathmandu Fun Park, Bhrikutimandap"],
   sources: [["Department of Tourism listing","https://www.tourismnpl.gov.np/LandmarkDetail/226/report"]]
 },
 "Natural History Museum": {
   summary: "Tribhuvan University’s museum below Swayambhu offers a close look at Nepal’s wildlife through preserved animals and insect specimens. It suits a short indoor outing and pairs naturally with a visit to the stupa above.",
   history: ["Tribhuvan University established the museum in 1975 at the foot of Swayambhu hill. Its collections document Nepal’s biodiversity for research and education.", "Tribhuvan University museum journal", "https://www.nepjol.info/index.php/JNHM/article/view/19043/15581"],
   stops: [["Natural History Museum, Swayambhu, Kathmandu", "Look for Nepal’s preserved animal and insect specimens in the museum galleries.", "Natural History Museum"]],
   bus: 'The museum recommends getting off at Swayambhu Ring Road and continuing on foot to the museum complex.',
   transport: 'Use the museum’s own pin for drop-off. It is below the stupa, so the Swayambhunath hilltop entrance is a separate stop.',
   visitNote: 'Check the museum’s seasonal hours and Saturday closures. Bring student identification if you plan to ask for the student rate.',
   sources: [["Natural History Museum visitor information","https://nhmnepal.edu.np/plan-your-visit/"]]
 },
 "Jump KTM, Jhamsikhel": {
   summary: "This indoor trampoline park in Jhamsikhel is a destination for active play in Lalitpur. Give children time to get comfortable on the equipment and check with the branch about age limits and suitable sessions.",
   stops: [["Jump Jhamsikhel Indoor Trampoline Park, Lalitpur", "Plan an indoor jumping session and ask the branch which session suits younger children.", "Jump KTM, Jhamsikhel"]],
   visitNote: 'Ask the Jhamsikhel branch about booking a session, grip socks, and rules for younger children.',
   sources: [["Jump KTM Jhamsikhel branch","https://jumpktm.com/jhamsikhel/"]]
 },
 "Whoopee Land": {
   summary: "Whoopee Land in Chobhar combines waterslides with amusement rides. Families can plan the outing around the water park or choose a shorter visit for the rides, depending on what is operating and what suits the group.",
   stops: [["Whoopee Land Amusement and Water Park, Chobhar", "Choose between the waterslides and amusement rides based on what is open.", "Whoopee Land"]],
   visitNote: 'Confirm that the water rides are running before you pack swimwear. Check each attraction’s height restrictions at the park.',
   sources: [["Whoopee Land updates","https://www.facebook.com/whoopee.land/"]]
 },
 "Kathmandu Fun Valley": {
   summary: "Kathmandu Fun Valley is a water park in Palanse near Sanga, built around pools and slides. It makes a different sort of family outing from the nearby temple and heritage visits, especially when the wave pool and water attractions are running.",
   stops: [["Kathmandu Fun Valley, Palanse, Bhaktapur", "Spend time at the wave pool and slides when those attractions are operating.", "Kathmandu Fun Valley"]],
   visitNote: 'Check the latest pool and slide availability before travelling and ask about children’s access to individual attractions.',
   sources: [["Kathmandu Fun Valley updates","https://www.facebook.com/kathmandufunvalley/"]]
 },
 'Chandragiri Hills': {
   summary: "The cable car climbs from the valley to a ridge with mountain views on clear days. Spend time around Bhaleshwor Mahadev Temple, then choose between a viewpoint break and the playground if you are visiting with children.",
   history: ["The ridge formed part of an old route into the Kathmandu Valley. Local tradition associates it with Prithvi Narayan Shah’s view of Kathmandu during the period of Gorkha expansion. Bhaleshwor Mahadev remains a pilgrimage stop on the ridge.", "Chandragiri Hills historical account", "https://www.chandragirihills.com/history/"],
   stops: [["Chandragiri Hills Cable Car","Ride up to the ridge for the viewpoint, Bhaleshwor Mahadev Temple or the playground."]],
   visitNote: 'Check the cable car’s operating hours and ask about the last ride down before spending time at the playground.',
   sources: [['Chandragiri Hills playground guide','https://www.chandragirihills.com/amusement-park/']]
 },
 "Bagh Bhairab Temple": {
   summary: "Bagh Bhairab Temple is a tiered shrine in old Kirtipur dedicated to Bhairab in tiger form. Make it the first stop on a walk through the old town and continue toward Uma Maheshwar Temple if you want a longer hilltop route.",
   history: ["Kirtipur Municipality traces the temple’s construction to the reign of King Shivadev, from 1099 to 1126, citing the Gopal dynasty chronicle. The town honours Bagh Bhairab as its protector and holds an annual jatra in his name.", "Kirtipur Municipality heritage record", "https://kirtipurmun.gov.np/heritage/baghbhairav/"],
   stops: [["Bagh Bhairab Temple, Kirtipur", "Look for the three-tiered Newar shrine dedicated to Bhairab in tiger form.", "Bagh Bhairab Temple"]],
   bus: 'Ask for a bus to Kirtipur and confirm where to get off for the old town. Continue on foot to Bagh Bhairab Temple and check your return service before setting out.',
   transport: 'Use the temple pin to plan a drop-off near old Kirtipur. Allow time to walk through the narrow lanes from where the vehicle can stop.',
   sources: [["Kirtipur Municipality heritage guide","https://kirtipurmun.gov.np/heritage/baghbhairav/"]],
 },
 "Indrayani Temple (Luti Ajima)": {
   summary: "Luti Ajima is a riverside Indrayani shrine near the Bishnumati. Combine time at the shrine with a short neighbourhood walk toward Shobha Bhagwati or nearby Bijeshwari.",
   stops: [["Lhuti Ajima, Kathmandu", "Pause at the Indrayani shrine by the Bishnumati before walking toward Shobha Bhagwati.", "Indrayani Temple (Luti Ajima)"]],
   sources: [["Tribhuvan University study of Kathmandu’s Ajima shrines","https://elibrary.tucl.edu.np/JQ99OgQIizUxyjI9nB0on9OyLkqsGIf4/api/core/bitstreams/90244c66-d453-4c08-84af-22a382c5e3da/content"]],
 },
 "Mhepi Ajima Temple": {
   summary: "A wooded hill separates this Ajima shrine from the busy streets of Mhepi below. The hillside setting makes it a compact worship stop with a short walk as part of the visit.",
   stops: [["Mhepi Ajima Temple, Kathmandu", "Follow the short uphill approach to the wooded shrine above the neighbourhood.", "Mhepi Ajima Temple"]],
   sources: [["Tribhuvan University study of Kathmandu’s Ajima shrines","https://elibrary.tucl.edu.np/JQ99OgQIizUxyjI9nB0on9OyLkqsGIf4/api/core/bitstreams/90244c66-d453-4c08-84af-22a382c5e3da/content"]],
 },
 "Bijeshwari Temple": {
   summary: "Bijeshwari is a Vajrayogini shrine near Shobha Bhagwati in Kathmandu. Its location close to the Bishnumati makes it easy to pair with Luti Ajima on a short pilgrimage walk.",
   stops: [["Bijeshwari Temple, Kathmandu", "Visit the Vajrayogini shrine near Shobha Bhagwati and pair it with nearby Luti Ajima.", "Bijeshwari Temple"]],
   sources: [["Wikimedia Commons temple photo and location","https://commons.wikimedia.org/wiki/File:Bijeshwari_Bajra_Yogini_Temple_Rajesh_Dhungana_Kathmandu_(3).jpg"]],
 },
 "Santaneshwor Mahadev Temple": {
   summary: "Santaneshwor Mahadev is a Shiva shrine in Jharuwarasi, Lalitpur. Set aside time for worship and the approach to the temple, then continue toward Godawari if you want a second stop in the area.",
   stops: [["Santaneshwor Mahadev Temple, Jharuwarasi", "Allow time for worship at this Shiva shrine before continuing toward Godawari.", "Santaneshwor Mahadev Temple"]],
   sources: [["Geotagged temple photo","https://commons.wikimedia.org/wiki/File:Shree_Santaneshwor_Mahadev_Temple_20180828_152945.jpg"]],
 },
 "Chonga Ganesh Temple": {
   summary: "The Chonga Ganesh shrine sits beside the Sallaghari pine grove in Bhaktapur. Start with darshan, then use a permitted spot in the grove if your group is staying for a picnic.",
   stops: [["Chonga Ganesh Temple, Sallaghari, Bhaktapur", "Start with darshan, then walk in the pine grove beside the temple.", "Chonga Ganesh Temple"]],
   sources: [["Bhaktapur.com temple guide","https://www.bhaktapur.com/discover/chonga-ganesh-temple/"]],
   picnic: "The pine grove near the temple is used for picnics. Ask locally where groups can set up and whether cooking is allowed.",
 },
 "Surya Binayak Temple": {
   summary: "This wooded hillside shrine is one of the Ganesh temples in the Four Binayak pilgrimage circuit. Allow time for the uphill approach, and plan a separate picnic stop on the surrounding hillside if visiting with a group.",
   stops: [["Surya Binayak Temple, Bhaktapur", "Follow the uphill approach through the wooded grounds to the Ganesh shrine.", "Surya Binayak Temple"]],
   sources: [["Bhaktapur.com visitor guide","https://www.bhaktapur.com/discover/suryabinayak-temple/"]],
   picnic: "Picnic areas are available on the surrounding hillside. Check reservation arrangements before bringing a group.",
 },
 "Tribhuvan Park": {
   summary: "Tribhuvan Park in Thankot is a public park with designated picnic grounds. It is suited to a group outing centred on a shared meal, with time to walk around the grounds before heading home.",
   stops: [["Tribhuwan Park, Thankot", "Take a walk around the grounds and settle in for a meal at a designated picnic spot.", "Tribhuvan Park"]],
   sources: [["Tribhuvan Park visitor information","https://tribhuwanpark.org/about"]],
   picnic: "Check the park’s current picnic booking options and fees before a group visit.",
 },
 "Balaju Baisdhara Park": {
   summary: "Balaju Baisdhara Park combines ponds, garden paths and historic stone water spouts. The park works for a short city walk or a longer picnic in a designated shelter.",
   stops: [["Balaju Baisdhara Park, Kathmandu", "Follow the garden paths past ponds and the historic stone water spouts.", "Balaju Baisdhara Park"]],
   sources: [
     ['Kathmandu Metropolitan City park information','https://onlineticketing.kathmandu.gov.np/'],
     ['Kathmandu Metropolitan City schedule of picnic shelters','https://law.kathmandu.gov.np/wp-content/uploads/2025/01/%E0%A4%95%E0%A4%BE%E0%A4%A0%E0%A4%AE%E0%A4%BE%E0%A4%A1%E0%A5%8C%E0%A4%82_%E0%A4%AE%E0%A4%B9%E0%A4%BE%E0%A4%A8%E0%A4%97%E0%A4%B0%E0%A4%AA%E0%A4%BE%E0%A4%B2%E0%A4%BF%E0%A4%95%E0%A4%BE%E0%A4%95%E0%A5%8B_%E0%A4%86%E0%A4%B0%E0%A5%8D%E0%A4%A5%E0%A4%BF%E0%A4%95_%E0%A4%90%E0%A4%A8_%E0%A5%A8%E0%A5%A6%E0%A5%AD%E0%A5%AB.pdf']
   ],
   picnic: "Kathmandu Metropolitan City lists designated picnic spaces here. Ask the park about reservations and current fees.",
 },
 "Manjushree Park": {
   summary: "Manjushree Park overlooks Chobhar Gorge, where the Bagmati passes through a narrow channel. The lawns and paths make the gorge an easy outing, and nearby Jal Binayak adds a temple stop if you have time.",
   history: ["Kirtipur Municipality records that the gorge area was quarried for limestone for more than two decades before it was developed into Manjushree Park.", "Kirtipur Municipality’s Chobhar account", "https://kirtipurmun.gov.np/heritage/jal-binayak/"],
   stops: [["Manjushree Park, Chobhar, Kirtipur", "Walk the park paths for a view of where the Bagmati narrows through Chobhar Gorge.", "Manjushree Park"]],
   sources: [["Visit Kirtipur park guide","https://visitkirtipur.com/places/"]],
   picnic: "A local family picnic spot. Check with park staff for garden rules and group arrangements.",
 },
 "Sangdaha Picnic Spot": {
   summary: "Sangdaha is a wooded picnic area in Changunarayan with shelters among the pines. Plan the outing around a picnic and a short walk in the forest, with Changu Narayan Temple as a separate stop.",
   stops: [["Sangdaha Picnic Spot, Changunarayan", "Look for the picnic shelters among the pines and take a short forest walk.", "Sangdaha Picnic Spot"]],
   sources: [["Changunarayan Municipality tourism inventory","https://changu-stag.naxa.com.np/tourism"]],
   picnic: "The municipal inventory lists several picnic areas in the forest. Confirm current access, facilities, and cooking rules before visiting.",
 },
 '1,000 Steps, Dhulikhel': {
   summary: "The stepped route climbs from Dhulikhel toward Kalidevi Temple. Make the staircase the main outing and take breaks on the way up, or combine part of the climb with an old-town walk.",
   stops: [
     ["1,000 Steps, Dhulikhel","Follow the stone steps uphill toward Kalidevi Temple, taking breaks as needed."],
     ["Kalidevi Temple, Dhulikhel", "Pause at the hilltop shrine after the climb up Dhulikhel’s 1,000 Steps.", "1,000 Steps, Dhulikhel"]
   ],
   sources: [["Dhulikhel Municipality’s Kalidevi and 1,000 Steps listing",'https://dhulikhelmun.gov.np/en/node/796']]
 },
 'Central Zoo': {
   summary: "Central Zoo in Jawalakhel brings together animal exhibits and spaces where children can take a break. Choose a manageable circuit through the grounds and leave time for the aquarium or children’s area.",
   history: ["Rana prime minister Juddha Shamsher established the zoo as a private collection in 1932. It passed into government ownership after the political changes of 1950 and opened to the public in 1956.", "National Trust for Nature Conservation report", "https://ntnc.org.np/sites/default/files/doc_publication/2018-11/NTNC_Annual%20Report%202014_Final_0.pdf"],
   stops: [["Central Zoo, Jawalakhel", "Pick a short loop through the animal exhibits and leave time for the aquarium or children’s area.", "Central Zoo"]],
   visitNote: 'Check the official visitor page for tickets and opening days. Plan a shorter loop or a break at the children’s area for younger visitors.',
   sources: [["National Trust for Nature Conservation visitor information",'https://ntnc.org.np/project/central-zoo']]
 },
 'Simulation Science Center': {
   summary: "This science centre in Chobhar is geared toward learning through hands-on demonstrations. It is a good fit for a school or family outing if a visit can be arranged, with Chobhar Gorge nearby for a second stop.",
   stops: [["Simulation Science Center, Chobhar, Kirtipur", "Ask which hands-on demonstrations are available and whether your visit needs to be arranged in advance.", "Simulation Science Center"]],
   mapUrl: 'https://maps.google.com/?cid=3963862198623667492',
   visitNote: 'Confirm whether the centre accepts walk-ins or requires advance arrangements for a group.',
   sources: [["Brihaspati Vidyasadan’s science centre visit",'https://bvs.edu.np/grade-2-explores-science/']]
 },
 'National Museum of Nepal': {
   summary: "The National Museum in Chhauni brings Nepal’s art and history together in several collections. Give yourself time to move between galleries, then pair the visit with Swayambhunath for a longer day in western Kathmandu.",
   history: ["The museum grew out of a weapons collection at Chhauni that was known as Chhauni Silkhana. The former arsenal building now houses the historical galleries. The complex later added galleries for art and Buddhist collections.", "National Museum conference contribution", "https://www.mccy.gov.sg/-/media/Mccy-Ca/Feature/Resources/Conference-Papers/5th-Asian-Museums-National-Museums-Association-Conference-Publication/ANMA-Publication.pdf"],
   stops: [
     ["National Museum of Nepal, Chhauni", "Explore the historical, art and Buddhist collections in the museum buildings.", "National Museum of Nepal"],
     ["Swayambhunath", "Follow the stupa circuit for a view over Kathmandu after the museum visit.", "Swayambhunath"]
   ],
   sources: [["Nepal Tourism Board’s National Museum guide",'https://ntb.gov.np/en/national-museum-of-nepal']]
 },
 'Adinath Lokeshwar Temple': {
   summary: "Adinath Lokeshwar Temple sits above Chobhar village. Its hilltop setting offers a different experience from the riverside shrine of Jal Binayak, which makes a natural second stop.",
   stops: [
     ["Adinath Lokeshwar Temple, Chobhar", "Start with the hilltop shrine above Chobhar before heading down toward the river.", "Adinath Lokeshwar Temple"],
     ["Jal Binayak Temple, Chobhar", "Visit the Ganesh shrine beside the Bagmati, close to Chobhar Gorge.", "Four Binayak Ganesh Temples"]
   ],
   sources: [["Kirtipur Municipality’s Adinath heritage listing",'https://kirtipurmun.gov.np/heritage/adinath/index.html?lang=en']]
 },
 'Bajra Barahi Temple': {
   summary: "A Barahi shrine stands in a wooded grove outside Chapagaon. The visit combines worship with a short walk among the trees, with Chapagaon nearby for a stop in town.",
   stops: [["Bajra Barahi Temple, Chapagaon", "Combine darshan at the Barahi shrine with a short walk in the surrounding grove.", "Bajra Barahi Temple"]],
   sources: [["Tribhuvan University study of Chapagaon",'https://conference.ioe.edu.np/ioegc8/papers/ioegc-8-029-80038.pdf']]
 },
 'Nil Barahi Temple': {
   summary: "Nil Barahi is a shrine in the wooded Tigani area near Bode. It is closely tied to the community’s masked dance tradition, and a visit can be paired with a walk through Bode itself.",
   history: ["The shrine is tied to Bode’s Nil Barahi masked dance. Performers take on the roles of deities during the community observance, keeping the shrine connected to festival life beyond daily worship.", "Bhaktapur.com’s account of the dance", "https://www.bhaktapur.com/nil-barahi-dance-an-amazing-dance-of-peace-war/"],
   stops: [["Nil Barahi Temple, Bode, Madhyapur Thimi", "Visit the wooded Tigani shrine associated with Bode’s Nil Barahi masked dance.", "Nil Barahi Temple"]],
   sources: [["Visit Madhyapur temple guide",'https://visitmadhyapur.com/place/%E0%A4%A8%E0%A5%80%E0%A4%B2%E0%A4%AC%E0%A4%BE%E0%A4%B0%E0%A4%BE%E0%A4%B9%E0%A5%80-%E0%A4%AE%E0%A4%A8%E0%A5%8D%E0%A4%A6%E0%A4%BF%E0%A4%B0/']]
 },
 'Chandeshwori Temple': {
   summary: "This goddess temple lies north of Banepa’s centre and offers a quieter stop after the market. The temple and bazaar make a compact half-day outing with two distinct sides of the town.",
   stops: [["Chandeshwori Temple, Banepa", "Pause in the courtyard to look at the tiered shrine and its carved details.", "Chandeshwori Temple Courtyard"]],
   sources: [["Banepa Municipality map",'https://banepamun.gov.np/sites/banepamun.gov.np/files/RoadMap1.pdf']]
 },
 'Matsya Narayan Temple': {
   summary: "The Vishnu shrine at Machhegaun is associated with Matsya, the deity’s fish incarnation. It is a local pilgrimage stop and the focus of the Purushottam Mela when the festival takes place.",
   stops: [["Matsya Narayan Temple, Machhegaun", "Visit the shrine dedicated to Matsya, the fish incarnation of Vishnu.", "Matsya Narayan Temple"]],
   sources: [["The Rising Nepal report from Machhegaun",'https://risingnepaldaily.com/news/29599']]
 },
 'Doleshwor Mahadev Temple': {
   summary: "Doleshwor Mahadev is a Shiva pilgrimage site in Sipadol, Bhaktapur, associated with Kedarnath. Allow time for worship here, then continue to Ashapuri if you are planning a longer temple outing.",
   history: ["In 2009, the chief priest of Kedarnath formally recognised Doleshwor Mahadev as the head of Kedarnath in pilgrimage tradition. The association draws Shiva devotees to the temple in Sipadol.", "Nepal Tourism Board pilgrimage guide", "https://ntb.gov.np/plan-your-trip/trip-ideas/pilgrimage-to-doleshwar-mahadev-temple"],
   stops: [
     ["Doleshwor Mahadev Temple, Sipadol, Bhaktapur", "See the Nandi statue in the courtyard and allow time for worship at the Shiva shrine.", "Doleshwor Mahadev Temple Courtyard"],
     ["Ashapuri Mahadev Temple, Suryabinayak", "Continue to the gold-roofed Shiva temple for a second pilgrimage stop.", "Ashapuri Mahadev Temple"]
   ],
   sources: [["Nepal Tourism Board pilgrimage guide",'https://ntb.gov.np/plan-your-trip/trip-ideas/pilgrimage-to-doleshwar-mahadev-temple']]
 },
 'Ashapuri Mahadev Temple': {
   summary: "Ashapuri Mahadev is a Shiva temple in Suryabinayak with a gold-roofed shrine. It can be a short visit on its own or the second stop on a pilgrimage outing from Doleshwor Mahadev.",
   history: ["Suryabinayak Municipality records a local legend that a Shiva linga appeared from the ground after villagers prayed during a drought. The story explains the shrine’s association with the fulfilment of wishes.", "Suryabinayak Municipality tourism profile", "https://suryabinayaktourism.digitalmun.com/sites/aashapuri-mahadev-temple/"],
   stops: [
     ["Ashapuri Mahadev Temple, Suryabinayak, Bhaktapur", "Look for the gold-roofed shrine and pause in the grounds after darshan.", "Ashapuri Mahadev Temple"],
     ["Doleshwor Mahadev Temple, Sipadol", "Stop at the Shiva shrine associated with Kedarnath and look for Nandi in the courtyard.", "Doleshwor Mahadev Temple Courtyard"]
   ],
   bus: 'Take a bus toward Suryabinayak Chowk. Ask about onward transport to Ashapuri before leaving the chowk, and confirm your return arrangements.',
   transport: 'Use the Ashapuri temple pin in Suryabinayak Ward 8 for the drive. Check the final approach and drop-off locally.',
   sources: [["Suryabinayak Municipality tourism guide",'https://suryabinayaktourism.digitalmun.com/sites/aashapuri-mahadev-temple/']]
 },
 'Ichangu Narayan Temple': {
   summary: "Ichangu Narayan is a Vishnu shrine west of Swayambhu. It is one of the valley’s four Narayan shrines and makes a short temple stop on the Nagarjun side of Kathmandu.",
   stops: [["Ichangu Narayan Temple, Ichangu, Nagarjun", "Spend time at one of the valley’s four Narayan shrines west of Swayambhu.", "Ichangu Narayan Temple"]],
   sources: [["Nepal Tourism Board pilgrimage guide",'https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/']]
 },
 'Shikali Temple': {
   summary: "Shikali Temple sits outside Khokana, away from the village’s central lanes. Start in the village at Rudrayani Temple or visit Shikali first, then walk into Khokana to see the setting of the community’s jatra.",
   stops: [["Shikali Temple, Khokana", "Walk out from the village to the shrine associated with Khokana’s Shikali Jatra.", "Shikali Temple"]],
   picnic: 'Ask locally where picnics are permitted around Khokana. Picnic facilities near the temple are unconfirmed.',
   sources: [["Tribhuvan University study of Shikali Jatra",'https://elibrary.tucl.edu.np/JQ99OgQIizUxyjI9nB0on9OyLkqsGIf4/api/core/bitstreams/fb8f7101-8702-47bc-8801-d66667dcc782/content']]
 },
 'Rudrayani Temple': {
   summary: "Rudrayani Temple stands in central Khokana among the village’s lanes and courtyards. It gives a visit a clear starting point before exploring the settlement or continuing out to Shikali Temple.",
   history: ["The village’s temple sits within a traditional pattern of courtyards and lanes. Nepal’s heritage submission for Khokana documents the village’s mother-goddess shrine and mustard-oil production as part of its living heritage.", "Khokana tentative-list entry", "https://whc.unesco.org/en/tentativelists/844/"],
   stops: [["Rudrayani Temple, Khokana", "Start at the village shrine before exploring Khokana’s central lanes and courtyards.", "Rudrayani Temple"]],
   sources: [["UNESCO Tentative List entry for Khokana",'https://whc.unesco.org/en/tentativelists/844/']]
 },
 'Karya Binayak Temple': {
   summary: "Karya Binayak is a Ganesh shrine in Bungamati. Pair it with the Rato Machhindranath shrine to experience two different places of worship on one village visit.",
   stops: [["Karya Binayak Temple, Bungamati", "Stop at the Ganesh shrine before continuing to the Rato Machhindranath temple.", "Karya Binayak Temple"]],
   sources: [["Wikimedia Commons temple record",'https://commons.wikimedia.org/wiki/File:KaryaBinayak_Temple.jpg']]
 },
 'Rato Machhindranath Temple': {
   summary: "This Bungamati shrine is associated with Rato Machhindranath, or Bung Dhya, the central deity of Lalitpur’s chariot festival. Plan the shrine as part of a walk through Bungamati and include Karya Binayak if you have time.",
   history: ["Tradition identifies Bungamati as the birthplace of the deity also known as Bung Dhya. Lalitpur’s chariot procession honours Rato Machhindranath, who is associated with rain and the agricultural season.", "Nepal Tourism Board festival guide", "https://ntb.gov.np/en/ratharohan-of-rato-machindranath"],
   stops: [["Rato Machhindranath Temple, Bungamati", "Visit the shrine of Bung Dhya, the deity honoured in Lalitpur’s chariot festival.", "Rato Machhindranath Temple"]],
   sources: [["Nepal Tourism Board festival guide",'https://ntb.gov.np/en/ratharohan-of-rato-machindranath']]
 },
 'Jal Binayak Temple': {
   summary: "Jal Binayak is a Ganesh shrine beside the Bagmati at Chobhar. The riverside approach and nearby gorge make it a natural companion to Manjushree Park or the hilltop Adinath temple.",
   history: ["Known locally as Kwoyena, Jal Binayak is one of the valley’s celebrated Ganesh shrines. Kirtipur Municipality dates the temple to the 16th century and notes that families come here for worship and ceremonies.", "Kirtipur Municipality heritage record", "https://kirtipurmun.gov.np/heritage/jal-binayak/"],
   stops: [["Jal Binayak Temple, Chobhar", "Visit the Ganesh shrine beside the Bagmati, close to Chobhar Gorge.", "Four Binayak Ganesh Temples"]],
   sources: [["Kirtipur Municipality’s Jal Binayak guide",'https://kirtipurmun.gov.np/heritage/jal-binayak/']]
 },
 'Dakshinkali Temple': {
   summary: "This shrine south of Pharping is dedicated to Kali. Give yourself time for darshan at the temple and continue to Pharping for another pilgrimage stop or a meal on the way back.",
   stops: [
     ["Dakshinkali Temple", "Allow time for darshan at the shrine dedicated to Kali.", "Dakshinkali Temple"],
     ["Pharping","Explore the town’s shrines after visiting Dakshinkali, or stop for a meal on the way back."]
   ],
   bus: 'Ask for a bus toward Dakshinkali via Pharping. Confirm whether the service goes to the temple area and where to catch the last return.',
   transport: 'Follow the road via Pharping and set the destination to Dakshinkali Temple. Check the closest drop-off or parking on arrival.',
   source: 'https://ntb.gov.np/en/dakshinkali-'
 },
 'Four Binayak Ganesh Temples': {
   summary: "This route links four Ganesh shrines across the Kathmandu Valley: Ashok Binayak in Maru, Chandra Binayak in Chabahil, Surya Binayak in Bhaktapur and Jal Binayak in Chobhar. Plan the circuit as several stops with travel time between them, or split it into two outings.",
   stops: [
     ['Ashok Binayak Temple, Maru','Also known as Maru Ganesh. Pair this stop with Kathmandu Durbar Square if time allows.'],
     ['Chandra Binayak Temple, Chabahil','Also known as Rakta Binayak. Use the Chabahil pin when planning your route.'],
     ['Surya Binayak Temple, Bhaktapur','Allow time for the uphill approach and check access if someone in your group has difficulty with stairs.'],
     ['Jal Binayak Temple, Chobhar','The shrine is by the Bagmati. Pair it with Kirtipur if you split the circuit over two days.']
   ],
   transport: 'Allow time for cross-valley travel between temples. Confirm local worship times and follow each shrine’s photography guidance.',
   sources: [
     ['Kirtipur Municipality’s Jal Binayak guide','https://kirtipurmun.gov.np/heritage/jal-binayak/'],
     ['Mary Shepherd Slusser’s Nepal Mandala','https://pahar.in/pahar/Books%20and%20Articles/Nepal/1982%20Nepal%20Mandala--Cultural%20Study%20of%20Kathmandu%20Valley%20vol%201%20Text%20by%20Slusser%20s.pdf'],
     ['Tribhuvan University CNAS journal on Valley shrine groupings','https://sources.mandala.library.virginia.edu/sites/mandala-sources.lib.virginia.edu/files/pdf-files/4270_0.pdf']
   ]
 },
 Nagarkot: {
   summary: "Nagarkot is a ridge getaway east of Kathmandu, best known for sunrise and long Himalayan views when the sky is clear. A short ridge walk and breakfast make an outing on their own, or you can add Changu Narayan on the return.",
   stops: [
     ["Nagarkot View Tower", "Go early for sunrise and look for the Himalayan skyline on a clear morning.", "Nagarkot View Tower"],
     ["Changu Narayan Temple", "Explore the hilltop Vishnu shrine and its stone sculpture on the return from Nagarkot.", "Changu Narayan Temple"]
   ],
   bus: 'Confirm the current boarding point and any connection for Nagarkot. If you are going for sunrise, check whether a passenger service runs early enough.',
   transport: 'Set directions to the specific viewpoint or hotel you plan to visit. The village and view tower are separate stops.',
   source: 'https://trade.ntb.gov.np/tourist-destination/hill-stations/'
 },
 'Changu Narayan Temple': {
   summary: "The hilltop Vishnu temple sits inside the traditional settlement of Changu Narayan. Give yourself time for the stone sculpture and temple precinct, then make a separate stop at the forest picnic ground if that is part of your plan.",
   history: ["A fifth-century inscription at Changu Narayan is one of the earliest recorded inscriptions in the Kathmandu Valley. The temple precinct and traditional settlement together form one of the Kathmandu Valley World Heritage monument zones.", "UNESCO Kathmandu Valley record", "https://whc.unesco.org/en/list/121/"],
   stops: [
     ["Changu Narayan Temple, Bhaktapur", "Take time for the stone sculptures and early inscription in the temple precinct.", "Changu Narayan Temple"],
     ["Changunarayan Picnic Spot, Bhaktapur","Look for the forest picnic ground near the bus park, separate from the temple."]
   ],
   picnic: 'The municipality lists a picnic ground in the forest near the bus park, separate from the temple. Check access, fees, and group arrangements before visiting.',
   picnicPoint: [27.716421, 85.432199],
   sources: [['Changunarayan Municipality tourism inventory','https://changu-stag.naxa.com.np/tourism']]
 },
 'Godawari Botanical Garden': {
   summary: "Paths wind past ponds and themed plant collections at the foot of Phulchowki. Look for orchids, medicinal plants and the rock garden, then take a break at one of the designated shelters if you brought a picnic.",
   history: ["King Mahendra inaugurated the Royal Botanical Garden in 1962. It was renamed the National Botanical Garden in 2006 and continues to conserve living plant collections for research and public education.", "National Botanical Garden fact sheet", "https://www.nbg.gov.np/pages/factsheet/"],
   stops: ['National Botanical Garden, Godawari, Lalitpur'],
   picnic: 'The garden lists designated rest areas for dry picnics. Bring prepared food. The garden prohibits cooking on site.',
   sources: [['National Botanical Garden visitor brochure','https://nbg.gov.np/wp-content/uploads/2022/12/Brochure-of-NBG.pdf']]
 },
 'Sankhu & Bajrayogini': {
   summary: "Start in Sankhu’s old Newar lanes, where public squares and small shrines break up the walk through town. Stone steps lead uphill to Bajrayogini, a pilgrimage site for Hindu and Buddhist devotees that can be paired with Salinadi.",
   history: ["Sankhu dates to the Lichchhavi period and stood on an old trade route to Tibet. Nepal’s Department of Archaeology dates the hill’s main Bajrayogini temple complex to the mid-17th century. Nepal submitted the town and temple together to UNESCO’s Tentative List in 2008.", "Sankhu and Vajrayogini tentative-list entry", "https://whc.unesco.org/en/tentativelists/5257/"],
   stops: ['Sankhu','Bajrayogini Temple, Sankhu'],
   picnic: 'Ask locally about permitted picnic areas around Sankhu. Picnic facilities near the temple are unconfirmed.',
   source: 'https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/'
 },
 Panauti: {
   summary: "Walk through Panauti’s old town toward Indreshwor Mahadev Temple and the riverside ghats. The compact lanes and courtyard shrines reward an unhurried visit, with Namo Buddha as a separate stop outside town.",
   history: ["At the meeting of the Roshi and Punyamati rivers, the medieval complex grew around Indreshwor Mahadev Temple, traditionally dated to 1294. It also includes the 17th-century Brahmayani Temple and historic rest houses. Nepal submitted the complex to UNESCO’s Tentative List in 1996.", "Panauti tentative-list entry", "https://whc.unesco.org/en/tentativelists/839/"],
   stops: [
     ["Indreshwor Mahadev Temple, Panauti", "See the timber carvings at the temple, then walk toward the riverside ghats.", "Panauti"],
     ["Namo Buddha","Continue from Panauti to the pilgrimage stupa and monastery as a separate stop."]
   ],
   source: 'https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/'
 },
 'Namo Buddha': {
   summary: "At Namo Buddha, a pilgrimage stupa and the hilltop Thrangu Tashi Yangtse Monastery offer two places to spend time. Plan for the uphill approach between stops and leave room for a quiet walk around the monastery grounds.",
   stops: [["Thrangu Tashi Yangtse Monastery, Namo Buddha", "Allow time for the uphill approach and a quiet walk around the monastery grounds.", "Namo Buddha"]],
   source: 'https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/'
 },
 Chitlang: {
   summary: "Chitlang is a village on the far side of Chandragiri, suited to a slow walk through the settlement before lunch. The outing can continue into the pasture country near Bheda Farm or down toward Kulekhani Reservoir.",
   history: ["Chitlang lies on a historic approach to the Kathmandu Valley through Chandragiri. The Nepal Tourism Board records a local account that members of the Malla royal family took refuge there after Prithvi Narayan Shah’s conquest in the 18th century.", "Nepal Tourism Board route history", "https://ntb.gov.np/en/plan-your-trip/trip-ideas/chandragiri-chitlang-kulekhani"],
   stops: [
     ["Chitlang", "Slow down with a walk through the village before returning from the reservoir.", "Chitlang"],
     ["Kulekhani Reservoir", "Continue from Chitlang toward Markhu for a change from village lanes to lakeside views.", "Kulekhani"]
   ],
   source: 'https://ntb.gov.np/plan-your-trip/trip-ideas/chandragiri-chitlang-kulekhani'
 },
 Kulekhani: {
   summary: "At Kulekhani, the hills around Indra Sarowar frame a quiet lakeside stop. Start near Markhu for a walk or a meal by the water, then continue to Chitlang if you have time for a village visit.",
   history: ["The area lies at the southern end of the historic Kathmandu approach through Chandragiri and Chitlang. Today Indra Sarowar is the reservoir that feeds the Kulekhani hydropower system.", "Nepal Electricity Authority project review", "https://www.nea.org.np/admin/assets/uploads/supportive_docs/69894782.pdf"],
   stops: [
     ["Indra Sarowar Reservoir", "Start near Markhu and pause by the water for a short walk or lunch.", "Kulekhani"],
     ["Chitlang", "Slow down with a walk through the village before returning from the reservoir.", "Chitlang"]
   ],
   source: 'https://ntb.gov.np/plan-your-trip/trip-ideas/chandragiri-chitlang-kulekhani'
 }
};
const find = id => document.getElementById('gj-' + id);
const view = root.dataset.view;
const query = new URLSearchParams(window.location.search);
const availableFilters = ['All',...categories,'Scenic dining'];
const startSelect = find('start');
const requestedOrigin = query.get('from');
const startingFrom = Object.hasOwn(origins,requestedOrigin) ? requestedOrigin : 'Kathmandu Valley';
const from = () => startSelect?.value || startingFrom;
if (startSelect) startSelect.value = startingFrom;
const requestedCity = query.get('city');
const city = Object.hasOwn(citySightseeing,requestedCity) ? requestedCity : null;
const requestedFilter = query.get('category');
const filter = availableFilters.includes(requestedFilter) ? requestedFilter : 'All';
const place = places.find(([name]) => name === query.get('place'));
const searchQuery = (query.get('q') || '').trim().slice(0,100);
const normalizeSearch = value => value.normalize('NFKC').toLowerCase().replace(/[^\p{L}\p{M}\p{N}]+/gu,' ').trim();
const searchIndex = new Map(places.map(([name,type,tags]) => {
 const cities = Object.keys(citySightseeing).filter(city => citySightseeing[city].includes(name));
 return [name,normalizeSearch([name,type,tags,...cities].join(' '))];
}));
function matchingPlaces(entries,value){
 const terms = normalizeSearch(value).split(' ').filter(Boolean);
 return entries.filter(([name]) => terms.length && terms.every(term => searchIndex.get(name).includes(term)));
}

const searchToggle = find('search-toggle');
const searchDialog = find('search-dialog');
const headerSearch = find('header-search');
const searchInput = find('search-input');
const suggestions = find('search-suggestions');
const searchMatches = find('search-matches');
const searchStatus = find('search-status');
const searchAll = find('search-all');

function showSuggestions(){
 const q = searchInput.value.trim();
 suggestions.hidden = !q;
 if (!q) return;
 const matches = matchingPlaces(places,q);
 const phrase = normalizeSearch(q);
 const rank = name => {
   const label = normalizeSearch(name);
   if (label === phrase) return 0;
   if (label.startsWith(phrase)) return 1;
   return label.includes(phrase) ? 2 : 3;
 };
 matches.sort(([a],[b]) => rank(a) - rank(b) || a.localeCompare(b));
 const items = matches.slice(0,6).map(([name,type,,,dining]) => {
   const item = document.createElement('li');
   const link = document.createElement('a');
   link.className = 'page-link search-match';
   link.href = pageUrl('destination.html',{place:name,q});
   const preview = document.createElement('span');
   preview.className = 'search-match-photo';
   const photo = photoFor(name,dining);
   if (photo) {
     const image = document.createElement('img');
     image.loading = 'lazy';
     image.decoding = 'async';
     setPhoto(image,photo,120);
     image.alt = '';
     preview.append(image);
   }
   const text = document.createElement('span');
   text.className = 'search-match-copy';
   const title = document.createElement('strong');
   title.textContent = name;
   const description = document.createElement('small');
   description.textContent = type;
   text.append(title,description);
   link.append(preview,text);
   item.append(link);
   return item;
 });
 searchMatches.replaceChildren(...items);
 searchStatus.textContent = matches.length === 1 ? '1 matching destination' :
   matches.length ? matches.length + ' matching destinations' : 'No matching destinations';
 searchAll.hidden = !matches.length;
 searchAll.href = pageUrl('explore.html',{q});
}
searchToggle.hidden = false;
searchInput.value = searchQuery;
searchToggle.addEventListener('click', () => {
 searchDialog.showModal();
 showSuggestions();
});
find('search-close').addEventListener('click',() => searchDialog.close());
searchDialog.addEventListener('click',event => {
 if (event.target !== searchDialog) return;
 const {left,right,top,bottom} = searchDialog.getBoundingClientRect();
 if (event.clientX < left || event.clientX > right || event.clientY < top || event.clientY > bottom) searchDialog.close();
});
searchInput.addEventListener('input',showSuggestions);
headerSearch.addEventListener('keydown',event => {
 if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
 if (event.target === searchInput && suggestions.hidden) showSuggestions();
 if (suggestions.hidden) return;
 const links = [...suggestions.querySelectorAll('a:not([hidden])')];
 if (!links.length) return;
 const current = links.indexOf(event.target);
 if (current === -1 && event.target !== searchInput) return;
 event.preventDefault();
 const direction = event.key === 'ArrowDown' ? 1 : -1;
 const next = event.target === searchInput && direction === -1 ? links.length - 1 : current + direction;
 (links[next] || searchInput).focus();
});
headerSearch.addEventListener('submit',event => {
 searchInput.value = searchInput.value.trim();
 if (!searchInput.value) {
   event.preventDefault();
   searchInput.reportValidity();
   return;
 }
 const originInput = headerSearch.elements.namedItem('from');
 originInput.value = from();
 originInput.disabled = originInput.value === 'Kathmandu Valley';
});

function pageUrl(path,params = {}){
 const search = new URLSearchParams();
 if (from() !== 'Kathmandu Valley') search.set('from',from());
 Object.entries(params).forEach(([key,value]) => {if (value) search.set(key,value);});
 return path + (search.size ? '?' + search : '');
}
function updateLinks(){
 root.querySelectorAll('a[data-filter],a[data-city],a[data-place],a[data-page],a[data-post]').forEach(link => {
   const data = link.dataset;
   if (data.post) {
     link.href = pageUrl('post.html',{slug:data.post});
   } else if (data.page === 'blog') {
     link.href = pageUrl('blog.html');
   } else if (data.place) {
     link.href = pageUrl('destination.html',{place:data.place,city,category:filter === 'All' ? null : filter,q:searchQuery});
   } else if (data.city) {
     link.href = pageUrl('explore.html',{city:data.city});
   } else if (data.filter) {
     link.href = pageUrl('explore.html',{
       city: link.closest('#gj-filters') ? city : null,
       category: data.filter === 'All' ? null : data.filter,
       q: link.closest('#gj-filters') ? searchQuery : null
     });
   } else if (data.page === 'home') {
     link.href = pageUrl('index.html');
   } else {
     link.href = pageUrl('explore.html',{city,category:filter === 'All' ? null : filter,q:searchQuery});
   }
 });
}
const chips = (items,active) => items.map(label => {
 const extra = label === 'Honeysun' ? ' aria-label="Honeysun, one-day trip for newlyweds"' : '';
 const current = active === label ? ' aria-current="true"' : '';
 return `<a class="page-link" href="${pageUrl('explore.html',{city,category:label === 'All' ? null : label})}" data-filter="${label}" data-reveal="fade-up"${current}${extra}>${label}</a>`;
}).join('');
const destinationGalleries = {
 Nagarkot: ['Nagarkot','Nagarkot View Tower'],
 'Bhaktapur Durbar Square': [
   'Bhaktapur Durbar Square Palace View','Bhaktapur Golden Gate',
   'Bhaktapur Durbar Square','Bhaktapur Durbar Square Panorama'
 ],
 'Chandeshwori Temple': ['Chandeshwori Temple Courtyard','Chandeshwori Temple Aerial View','Chandeshwori Temple'],
 'Doleshwor Mahadev Temple': ['Doleshwor Mahadev Temple Courtyard','Doleshwor Mahadev Temple Nandi','Doleshwor Mahadev Temple'],
 'Four Binayak Ganesh Temples': ['Four Binayak Ganesh Temples','Surya Binayak Temple'],
 'Jal Binayak Temple': ['Four Binayak Ganesh Temples']
};
const photoFor = (name,dining) => dining ? photos.Dining : photos[destinationGalleries[name]?.[0] || name];
const thumbnail = (photo,width = 960) => {
 if (!photo.commons) return photo.src;
 const path = photo.src.split('/commons/')[1];
 const filename = path.split('/').pop();
 return 'https://thumb.wikimedia.org/wikipedia/commons/thumb/' + path + '/' + width + 'px-' + filename;
};
const cards = (entries,distances) => entries.map(([name,type,tags,,dining]) => {
 const photo = photoFor(name,dining);
 return `<div class="col" data-reveal="fade-up"><a class="place page-link" href="${pageUrl('destination.html',{place:name})}" data-place="${name}">
   <span class="art">${photo ? `<img src="${thumbnail(photo)}" data-original="${photo.src}" alt="${photo.alt}" loading="lazy">` : '<span class="photo-pending">Photo pending</span>'}</span>
   <span class="info"><span class="meta">${type}${dining ? ' · Sample listing' : ''}</span><strong>${name}</strong>${distances ? `<span class="place-distance">${Number.isFinite(distances[name]) ? (distances[name] < 1 ? 'Under 1' : '≈ ' + Math.round(distances[name])) + ' km away' : 'Distance unavailable'}</span>` : ''}<span class="card-foot">${dining ? dining.view + ' · About 1 hr (sample)' : tags.split('|').slice(0,2).join(' · ')} <span aria-hidden="true">↗</span></span></span>
 </a></div>`;
}).join('');
function renderCities(){
 find('cities').innerHTML = Object.entries(citySightseeing).map(([name,sights]) => {
   const photo = photos[sights[0]];
   const count = cityPlaces(name).length;
   const countLabel = count + (count === 1 ? ' place to see' : ' places to see');
   return `<div class="col" data-reveal="fade-up"><a class="place city-card page-link" href="${pageUrl('explore.html',{city:name})}" data-city="${name}">
     <span class="art"><img src="${thumbnail(photo)}" data-original="${photo.src}" alt="" loading="lazy"></span>
     <span class="info"><strong>${name}</strong><span class="card-foot"><span>${countLabel}</span><span aria-hidden="true">↗</span></span></span>
   </a></div>`;
 }).join('');
}
function results(){
 const origin = origins[startingFrom];
 const distances = Object.fromEntries(places.map(([name]) => {
   const picnicPoint = filter === picnicCategory ? destinationGuides[name]?.picnicPoint : null;
   return [name,distanceFrom(origin,picnicPoint || placePoints[name])];
 }));
 const entries = (city ? cityPlaces(city) : places).filter(([,type,tags]) =>
   filter === 'All' || filter === type || tags.split('|').includes(filter));
 const matches = searchQuery ? matchingPlaces(entries,searchQuery) : entries;
 if (!city) matches.sort((a,b) => distances[a[0]] - distances[b[0]]);
 let title = 'Explore nearby';
 if (searchQuery) title = 'Search results for “' + searchQuery + '”';
 else if (city) title = 'Sightseeing in ' + city;
 else if (filter === 'Scenic dining') title = filter;
 else if (filter === picnicCategory) title = 'Picnic spots';
 else if (filter === 'Kids') title = 'Places to go with kids';
 find('results-title').textContent = title;
 document.title = title + ' — Ghumna Jaun';
 find('origin').textContent = city ? 'Browse by city' : 'From ' + startingFrom;
 find('distance-note').hidden = Boolean(city);
 const startDialog = find('start-dialog');
 const originSelect = find('start-origin');
 find('change-start').addEventListener('click',() => {
   originSelect.value = startingFrom;
   startDialog.showModal();
 });
 find('start-close').addEventListener('click',() => startDialog.close());
 startDialog.addEventListener('click',event => {
   if (event.target !== startDialog) return;
   const bounds = startDialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) startDialog.close();
 });
 find('start-form').addEventListener('submit',event => {
   event.preventDefault();
   const url = new URL(window.location.href);
   if (originSelect.value === 'Kathmandu Valley') url.searchParams.delete('from');
   else url.searchParams.set('from',originSelect.value);
   url.hash = '';
   window.location.assign(url.href);
 });
 find('count').textContent = matches.length + (matches.length === 1 ? ' place' : ' places');
 const filters = ['All',...categories,'Scenic dining'].filter(label =>
   !city || label === 'All' || cityPlaces(city).some(([,type,tags]) => label === type || tags.split('|').includes(label))
 );
 find('filters').innerHTML = chips(filters,filter);
 const emptyMessage = searchQuery ? 'No places match your search. Try a different name, area or activity.' : 'No places match this filter.';
 find('list').innerHTML = matches.length ? cards(matches,city ? null : distances) : '<p>' + emptyMessage + '</p>';
}
function mapSearch(query){
 return 'https://www.google.com/maps/search/?' + new URLSearchParams({api:'1',query});
}
function externalLink(url,label,className = 'page-link'){
 return `<a class="${className}" href="${url}" target="_blank" rel="noopener">${label} <span class="link-arrow" aria-hidden="true">↗</span></a>`;
}
function embedPlaceholder(label){
 return `<div class="consent-placeholder"><p>${label} loads after you accept optional embedded content.</p><button type="button" data-cookie-preferences>Cookie preferences</button></div>`;
}
function destinationMap(name,point){
 const [lat,lon] = point;
 const params = new URLSearchParams({
   bbox: [lon - 0.025,lat - 0.015,lon + 0.025,lat + 0.015].join(','),
   layer: 'mapnik',
   marker: point.join(',')
 });
 return `<div class="consent-embed consent-map">${embedPlaceholder('This map')}<iframe class="guide-map" title="Map of ${name}" loading="lazy" data-consent-src="https://www.openstreetmap.org/export/embed.html?${params}" hidden></iframe></div>`;
}
const tripDetails = {
 'Kathmandu Fun Park': {
   location: 'Bhrikutimandap, Kathmandu',
   photos: ['Kathmandu Fun Park','Bhrikutimandap Fun Park View'],
   facts: [
     ['Opening hours','Check before you go','Confirm ride availability at the park'],
     ['Entry and rides','Ask at the gate','Check entry and individual ride charges'],
     ['Suggested visit','1–2 hours','Allow extra time for queues']
   ],
   activities: [
     ['Choose a few rides','Ask which rides are operating and check height or age requirements at each ride.',null,'Kathmandu Fun Park'],
     ['Take a snack break','The Department of Tourism lists places to sit and eat in the park.'],
     ['Walk around Bhrikutimandap','Use Pradarshani Marg to reach the park and explore the surrounding area.']
   ],
   source: ['Department of Tourism listing','https://www.tourismnpl.gov.np/LandmarkDetail/226/report'],
   visit: [
     ['Tickets and hours',['Confirm opening hours and current prices at the entrance. Ask how individual rides are priced.']],
     ['Visiting with kids',['Check ride restrictions before buying tickets.','Plan a meeting spot and allow time for breaks.']]
   ],
   transport: [
     ['By bus','If your bus stops near Ratna Park, continue on foot to Pradarshani Marg in Bhrikutimandap.'],
     ['By car or taxi','Use the Kathmandu Fun Park pin for drop-off and check parking near the entrance.']
   ],
   transportPin: ['Ratna Park, Kathmandu, Nepal','Ratna Park'],
   meals: [
     ['Before the visit','The BLVD','A sit-down meal at Hotel Royal Singi in Kamaladi if you’re approaching from Durbar Marg.', 'https://royalsingi.com/dine-drink/the-blvd/','Restaurant details'],
     ['During the visit','Snacks at Bhrikutimandap','Take a break inside the park or look for food around Pradarshani Marg.'],
     ['After the visit','Basantapur Food Court','Several food stalls in Basantapur if you’re heading toward Durbar Square.', 'https://food-court.basantapur.com/','Food court website']
   ],
   stays: [['Hotel Royal Singi','Kamaladi, Kathmandu','https://royalsingi.com/rooms/']],
   plan: [
     ['Morning','Start with the park rides, checking availability at the gate.'],
     ['Midday','Take a food break in Bhrikutimandap or head to Basantapur for lunch.'],
     ['Afternoon','Add a short walk around Ratna Park or continue to Kathmandu Durbar Square.']
   ],
   nearbyNote: 'Pair the park with a nearby green space or a heritage walk.',
   nearby: [['Shankhadhar Park, Ratna Park, Kathmandu, Nepal','Ratna Park'],['Kathmandu Durbar Square, Nepal','Kathmandu Durbar Square']]
 },
 'Sankhu & Bajrayogini': {
   location: 'Shankharapur, Kathmandu',
   foodArea: 'Sankhu Bazaar, Shankharapur, Nepal',
   photos: ['Sankhu & Bajrayogini','Bajrayogini Temple View'],
   facts: [
     ['Outing','Heritage and pilgrimage','Sankhu town and hilltop temple'],
     ['Temple approach','Uphill walk with steps','Allow breaks on the climb'],
     ['Suggested visit','Half a day','Allow extra time for Salinadi']
   ],
   activities: [
     ['Explore historic Sankhu','Start in the bazaar and walk through the old settlement.'],
     ['Climb to Bajrayogini Temple','Follow the uphill approach to the shrine above town, allowing time for the stone steps.',null,'Sankhu & Bajrayogini'],
     ['Visit Salinadi','Add the nearby pilgrimage area if there’s time after the temple.']
   ],
   source: ['Nepal Tourism Board pilgrimage guide','https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/'],
   visit: [
     ['Temple access',['Confirm current access or festival arrangements locally.','Follow posted guidance on footwear and photography.']],
     ['Walking and picnic planning',['Wear shoes with grip for the stone steps and carry water.','Ask locally where picnics are permitted around Sankhu.']]
   ],
   transport: [
     ['By bus','Take a bus to Sankhu and confirm your current boarding point and return departure locally.'],
     ['By car or taxi','Start in Sankhu for the town walk. Confirm drop-off and parking before the uphill temple approach.']
   ],
   transportPin: ['Sankhu Bus Park, Shankharapur, Nepal','Sankhu Bus Park'],
   meals: [
     ['On the way','Jorpati food stops','Find a snack before Sankhu if your route passes through Jorpati.',mapSearch('Restaurants in Jorpati, Kathmandu, Nepal'),'Find places in Jorpati'],
     ['During the visit','Sankhu Bazaar','Return to the bazaar for a meal after the temple walk.',mapSearch('Restaurants near Sankhu Bazaar, Shankharapur, Nepal'),'Find places in Sankhu'],
     ['On the way back','Boudha dining','Stop for a meal near Boudhanath if you’re returning through Boudha.',mapSearch('Restaurants near Boudhanath Stupa, Kathmandu, Nepal'),'Find places in Boudha']
   ],
   plan: [
     ['Morning','Walk through Sankhu Bazaar and the historic settlement.'],
     ['Midday','Climb to Bajrayogini Temple, then return to town for lunch.'],
     ['Afternoon','Visit Salinadi or head back through Boudha for a meal.']
   ],
   nearbyNote: 'Salinadi is another pilgrimage stop near Sankhu. Allow more time if you plan to visit both.',
   nearby: [['Sankhu Bazaar, Shankharapur, Nepal','Sankhu Bazaar'],['Salinadi, Sankhu, Nepal','Salinadi']]
 }
};
function standardTripDetails(name,type){
 const guide = destinationGuides[name];
 const fee = entryFees[name];
 const stops = guide.stops.map(stop => Array.isArray(stop) ? stop : [stop]);
 const picnic = guide.picnic;
 const visit = [[picnic ? 'Picnic planning' : 'Before you go',[
   picnic || guide.visitNote || 'Check opening hours and access before leaving, especially if visiting on a holiday.'
 ]]];
 if (fee) {
   visit.unshift(['Published entry fees',[
     'SAARC visitors · NPR ' + fee[0].toLocaleString('en'),
     'Other international visitors · NPR ' + fee[1].toLocaleString('en')
   ],['Nepal Tourism Board fee table',entryFeeSource]]);
 }
 const sources = guide.sources || (guide.source ? [['Nepal Tourism Board visitor guide',guide.source]] : []);
 const nearby = stops.slice(1).map(([label]) => [label + ', Nepal',label]);
 if (name === 'Shikali Temple') {
   for (const temple of ['Rudrayani Temple','Karya Binayak Temple','Rato Machhindranath Temple','Jal Binayak Temple']) {
     nearby.push([null,temple,pageUrl('destination.html',{place:temple,category:'Dharmik'})]);
   }
 }
 if (guide.picnicPoint) nearby.push([guide.picnicPoint.join(','),'Picnic ground pin']);
 return {
   location: type,
   photos: destinationGalleries[name] || (photos[name] ? [name] : []),
   facts: [
     ['Outing',type.split(' · ')[0],name === 'Four Binayak Ganesh Temples' ? 'Four stops across the valley' : ''],
     [fee ? 'Entry fees' : 'Visiting',fee ? 'See ticket details' : 'Check local access',''],
     [picnic ? 'Picnics' : 'Visit plan',picnic ? 'See picnic guidance' : 'See places to visit','']
   ],
   activities: stops.map(([label,note,photo]) => [label,note,mapSearch(label + ', Nepal'),photo]),
   sources,
   visit,
   transport: [
     ['By bus',guide.bus || 'Confirm the boarding point, nearest drop-off and last return with a local operator. Use the map to plan the walk from the stop.'],
     ['By car or taxi',guide.transport || 'Use the destination pin for directions. Check where vehicles can drop passengers off and whether there is parking nearby.']
   ],
   plan: dayPlans[name]?.map(stop => stop.split(' · ')) || stops.map(([label],index) => [
     index === 0 ? 'Start here' : 'If you have time',label
   ]),
   nearby,
   nearbyNote: nearby.length ? 'Open each location separately to plan the order of your stops.' : ''
 };
}
function renderTrip(name,destination,directionsUrl,type,dining){
 const trip = find('trip-template').content.cloneNode(true);
 const slot = key => trip.querySelector('[data-trip-' + key + ']');
 const isGarden = name === 'Godawari Botanical Garden';
 const details = isGarden ? null : tripDetails[name] || (dining ? {
   location: 'Scenic dining · Sample listing',
   intro: 'A sample scenic dining stop with ' + dining.view.toLowerCase() + ' and ' + dining.seating.toLowerCase() + '. The sample menu and budget illustrate what a confirmed venue listing could include.',
   photos: ['Dining'],
   facts: [['View',dining.view,''],['Seating',dining.seating,''],['Sample budget',dining.budget,'']],
   activities: dining.menu.map(item => [item]),
   visit: [['View and seating',[dining.view,dining.seating]]],
   transport: [
     ['By bus','The venue address is pending. Check the nearest stop once a restaurant is confirmed.'],
     ['By car or taxi','Confirm the restaurant address, drive time, and parking before travelling.']
   ],
   nearby: []
 } : standardTripDetails(name,type));
 const point = name === 'Four Binayak Ganesh Temples' ? placePoints['Kathmandu Durbar Square'] : placePoints[name];
 const originLabel = startingFrom === 'Kathmandu Valley' ? 'central Kathmandu' : startingFrom;
 const distance = distanceFrom(origins[startingFrom],point);
 if (details) {
   slot('title').textContent = name;
   slot('location').textContent = details.location.toUpperCase();
   slot('slider').setAttribute('aria-label',name + ' photos');
   slot('slider').querySelector('.trip-photo-dots').setAttribute('aria-label','Choose a destination photo');
   slot('slides').innerHTML = details.photos.map((key,index) =>
     `<div class="swiper-slide"><img data-trip-photo="${key.replaceAll('&','&amp;')}" alt="" ${index ? 'loading="lazy"' : 'fetchpriority="high"'}></div>`
   ).join('');
   details.facts.forEach(([label,value,note],index) => {
     const [term,main,caption] = slot('facts').children[index].children;
     term.textContent = label;
     main.textContent = value;
     caption.textContent = note;
   });
   slot('activities').innerHTML = details.activities.map(([title,description,url,photo]) => {
     const image = photos[photo] ? `<img class="trip-stop-photo" data-trip-photo="${photo.replaceAll('&','&amp;')}" alt="" loading="lazy" decoding="async">` : '';
     return `<li>${image}<div><h3>${title}</h3>${description ? '<p>' + description + '</p>' : ''}${url ? externalLink(url,'View ' + title + ' on map','trip-inline-link') : ''}</div></li>`;
   }).join('');
   const sources = details.sources || (details.source ? [details.source] : []);
   slot('feature-link').innerHTML = sources.map(([label,url]) => externalLink(url,label,'trip-inline-link')).join('');
   const feature = slot('feature');
   if (details.photos[1]) {
     feature.querySelector('img').dataset.tripPhoto = details.photos[1];
     feature.querySelector('figcaption').textContent = photos[details.photos[1]].alt;
   } else {
     feature.remove();
   }
   trip.querySelector('#gj-vlog-title').remove();
   slot('visit').innerHTML = details.visit.map(([title,notes,source]) =>
     `<div class="trip-card"><h3>${title}</h3><ul class="trip-visit-list">${notes.map(note => '<li>' + note + '</li>').join('')}</ul>${source ? externalLink(source[1],source[0]) : ''}</div>`
   ).join('');
   slot('transport').innerHTML = details.transport.map(([title,note]) =>
     `<div><h3>${title}</h3><p>${note}</p></div>`
   ).join('') + '<div class="trip-text-links" data-trip-travel-links></div>';
   slot('meal-note').remove();
   slot('meals').innerHTML = (details.meals || []).map(([stage,title,note,url,label]) =>
     `<article class="trip-card"><p class="eyebrow">${stage.toUpperCase()}</p><h3>${title}</h3><p>${note}</p>${
       url ? '<div class="trip-text-links">' + externalLink(url,label) + '</div>' : ''
     }</article>`
   ).join('');
   if (!details.meals) slot('meals').remove();
   const stays = slot('stays');
   if (details.stays) {
     stays.innerHTML = details.stays.map(([label,location,url]) =>
       `<li><strong>${label}</strong> <span>${location}</span> ${externalLink(url,'Hotel website')}</li>`
     ).join('');
   } else {
     stays.remove();
   }
   slot('plan').innerHTML = (details.plan || []).map(([stage,note]) =>
     `<li><strong>${stage}</strong><span>${note}</span></li>`
   ).join('');
   slot('nearby-note').textContent = details.nearbyNote || '';
   if (!details.nearby.length) slot('nearby-note').closest('.trip-card').hidden = true;
   if (!details.photos.length) slot('slider').remove();
   if (dining) {
     for (const [id,label] of [['gj-things','Menu & prices'],['gj-visit','View & seating']]) {
       trip.querySelector('#' + id + '-title').textContent = label;
       trip.querySelector('a[href="#' + id + '"]').textContent = label;
     }
     for (const id of ['gj-eat','gj-day']) {
       trip.querySelector('#' + id).hidden = true;
       trip.querySelector('a[href="#' + id + '"]').remove();
     }
     trip.querySelector('.trip-mobile-bar').hidden = true;
   }
 }
 slot('intro').textContent = destinationGuides[name]?.summary || details?.intro || '';
 const history = destinationGuides[name]?.history;
 if (history) {
   const [copy,label,url] = history;
   slot('history').textContent = copy;
   slot('history-source').innerHTML = externalLink(url,label);
   slot('history').closest('section').hidden = false;
   slot('history-jump').hidden = false;
 }
 slot('origin').textContent = 'From ' + originLabel;
 slot('distance').textContent = distance < 1 ? 'Under 1 km' : 'About ' + Math.round(distance) + ' km';
 if (!point) slot('origin').parentElement.remove();
 trip.querySelectorAll('[data-trip-photo]').forEach(image => {
   setPhoto(image,photos[image.dataset.tripPhoto],image.classList.contains('trip-stop-photo') ? 250 : undefined);
 });
 const photoDialog = find('stop-photo-dialog');
 const largePhoto = find('stop-photo-large');
 const photoStatus = find('stop-photo-status');
 const stopImages = [...trip.querySelectorAll('.trip-stop-photo')];
 const photoPosition = find('stop-photo-position');
 const photoThumbnails = find('stop-photo-thumbnails');
 const thumbnailButtons = stopImages.map((image,index) => {
   const button = document.createElement('button');
   button.type = 'button';
   button.setAttribute('aria-label','Show photo of ' + image.closest('li').querySelector('h3').textContent);
   button.setAttribute('aria-pressed','false');
   const preview = document.createElement('img');
   preview.width = 60;
   preview.height = 44;
   preview.loading = 'lazy';
   preview.decoding = 'async';
   setPhoto(preview,photos[image.dataset.tripPhoto],120);
   preview.alt = '';
   button.append(preview);
   button.addEventListener('click',() => {
     if (index !== activePhoto) showStopPhoto(index);
   });
   return button;
 });
 photoThumbnails.replaceChildren(...thumbnailButtons);
 photoThumbnails.hidden = stopImages.length < 2;
 const photoMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
 let activePhoto = 0;
 let photoChange = 0;
 let photoFade = null;
 function revealActiveThumbnail(){
   if (!photoDialog.open || photoThumbnails.hidden) return;
   const strip = photoThumbnails.getBoundingClientRect();
   const selected = thumbnailButtons[activePhoto].getBoundingClientRect();
   const centered = photoThumbnails.scrollLeft + selected.left - strip.left -
     (photoThumbnails.clientWidth - selected.width) / 2;
   const maximum = photoThumbnails.scrollWidth - photoThumbnails.clientWidth;
   photoThumbnails.scrollTo({
     left:Math.max(0,Math.min(maximum,centered)),
     behavior:photoMotion.matches ? 'instant' : 'smooth'
   });
 }
 new ResizeObserver(revealActiveThumbnail).observe(photoThumbnails);
 async function showStopPhoto(index){
   const change = ++photoChange;
   activePhoto = (index + stopImages.length) % stopImages.length;
   const image = stopImages[activePhoto];
   const photo = photos[image.dataset.tripPhoto];
   const opacity = getComputedStyle(largePhoto).opacity;
   photoFade?.cancel();
   if (photoDialog.open && !largePhoto.hidden && !photoMotion.matches) {
     photoFade = largePhoto.animate([{opacity},{opacity:0}],{duration:120,easing:'ease-out',fill:'forwards'});
     await photoFade.finished.catch(() => {});
     if (change !== photoChange) return;
   }
   largePhoto.style.opacity = '0';
   photoFade?.cancel();
   find('stop-photo-title').textContent = image.closest('li').querySelector('h3').textContent;
   find('stop-photo-caption').textContent = photo.alt;
   photoPosition.textContent = (activePhoto + 1) + ' of ' + stopImages.length;
   thumbnailButtons.forEach((button,index) => button.setAttribute('aria-pressed',String(index === activePhoto)));
   revealActiveThumbnail();
   photoStatus.textContent = 'Loading photo…';
   largePhoto.hidden = false;
   setPhoto(largePhoto,photo,1280);
 }
 find('stop-photo-controls').hidden = stopImages.length < 2;
 find('stop-photo-previous').addEventListener('click',() => showStopPhoto(activePhoto - 1));
 find('stop-photo-next').addEventListener('click',() => showStopPhoto(activePhoto + 1));
 let photoSwipe = null;
 largePhoto.addEventListener('pointerdown',event => {
   if (!event.isPrimary) {
     photoSwipe = null;
     return;
   }
   if (event.pointerType === 'mouse' || stopImages.length < 2) return;
   photoSwipe = {id:event.pointerId,x:event.clientX,y:event.clientY};
   largePhoto.setPointerCapture(event.pointerId);
 });
 largePhoto.addEventListener('pointerup',event => {
   const start = photoSwipe;
   photoSwipe = null;
   if (!start || start.id !== event.pointerId) return;
   const horizontal = event.clientX - start.x;
   const vertical = event.clientY - start.y;
   if (Math.abs(horizontal) < 48 || Math.abs(horizontal) < Math.abs(vertical) * 1.3) return;
   showStopPhoto(activePhoto + (horizontal < 0 ? 1 : -1));
 });
 const cancelPhotoSwipe = () => { photoSwipe = null; };
 largePhoto.addEventListener('pointercancel',cancelPhotoSwipe);
 largePhoto.addEventListener('lostpointercapture',cancelPhotoSwipe);
 photoDialog.addEventListener('close',cancelPhotoSwipe);
 photoDialog.addEventListener('keydown',event => {
   if (!['ArrowLeft','ArrowRight'].includes(event.key) || stopImages.length < 2) return;
   event.preventDefault();
   showStopPhoto(activePhoto + (event.key === 'ArrowRight' ? 1 : -1));
 });
 stopImages.forEach((image,index) => {
   const button = document.createElement('button');
   button.type = 'button';
   button.className = 'trip-stop-photo-button';
   const title = image.closest('li').querySelector('h3').textContent;
   button.setAttribute('aria-label','View larger photo of ' + title);
   button.setAttribute('aria-haspopup','dialog');
   button.setAttribute('aria-controls',photoDialog.id);
   image.before(button);
   button.append(image);
   button.addEventListener('click',() => {
     showStopPhoto(index);
     photoDialog.showModal();
     revealActiveThumbnail();
   });
 });
 largePhoto.addEventListener('load',() => {
   photoStatus.textContent = '';
   photoFade?.cancel();
   largePhoto.style.opacity = '1';
   if (photoDialog.open && !photoMotion.matches) {
     photoFade = largePhoto.animate([{opacity:0},{opacity:1}],{duration:220,easing:'ease-out'});
   }
 });
 largePhoto.addEventListener('error',() => {
   if (!largePhoto.dataset.original) photoStatus.textContent = 'This photo could not load. Try another photo or close the popup and try again.';
 });
 find('stop-photo-close').addEventListener('click',() => photoDialog.close());
 photoDialog.addEventListener('close',() => {
   photoChange++;
   photoFade?.cancel();
   largePhoto.style.opacity = '1';
 });
 photoDialog.addEventListener('click',event => {
   if (event.target !== photoDialog) return;
   const bounds = photoDialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) photoDialog.close();
 });
 trip.querySelectorAll('[data-trip-directions]').forEach(link => {
   if (dining) {
     link.hidden = true;
     return;
   }
   link.href = directionsUrl;
   if (name === 'Four Binayak Ganesh Temples') link.textContent = 'Open temple route ↗';
   link.setAttribute('aria-label','Get directions from ' + originLabel + ' in Google Maps');
 });
 trip.querySelectorAll('[data-trip-venue]').forEach(link => {
   link.href = mapSearch(link.dataset.tripVenue);
 });
 trip.querySelectorAll('[data-trip-stop]').forEach(link => {
   const route = new URL(directionsUrl);
   const params = route.searchParams;
   if (link.hasAttribute('data-trip-return')) {
     const origin = params.get('origin');
     params.set('origin',params.get('destination'));
     params.set('destination',origin);
   }
   params.set('waypoints',link.dataset.tripStop);
   link.href = route.href;
 });
 if (point) {
   slot('map').insertAdjacentHTML('afterbegin',destinationMap(name,point));
   const mapPin = name === 'Four Binayak Ganesh Temples' ? 'Ashok Binayak Temple, Maru' : destination;
   slot('map-link').href = destinationGuides[name]?.mapUrl || mapSearch(mapPin + ', Nepal');
 } else {
   slot('map').remove();
 }
 const transportPin = isGarden ? ['Godawari Bus Park, Lalitpur, Nepal','Godawari Bus Park'] : details.transportPin;
 slot('travel-links').innerHTML = [
   ...(transportPin ? [transportPin] : []),
   ['Petrol pumps near ' + destination,'Nearby petrol pumps'],
   ['EV charging near ' + destination,'Nearby EV charging']
 ].map(([query,label]) => externalLink(mapSearch(query),label)).join('');
 if (dining) slot('travel-links').remove();
 slot('nearby-links').innerHTML = (details?.nearby || [
   ['Godawari Kunda, Lalitpur, Nepal','Godawari Kunda'],
   ['Naudhara, Godawari, Lalitpur, Nepal','Naudhara']
 ]).map(([query,label,url]) => url ? `<a class="page-link" href="${url}">${label} ↗</a>` : externalLink(mapSearch(query),label)).join('');
 const foodArea = details?.foodArea || destination;
 slot('food-links').innerHTML =
   externalLink(mapSearch('Restaurants near ' + foodArea),details ? 'Find nearby restaurants' : 'More restaurants near the garden') +
   externalLink(mapSearch('Hotels near ' + foodArea),details ? 'Find nearby stays' : 'More stays near the garden');
 const currentTags = place[2].split('|');
 const related = places.filter(entry => entry[0] !== name && !entry[4]).map(entry => {
   const sharedTags = entry[2].split('|').filter(tag => currentTags.includes(tag)).length;
   const distance = point ? distanceFrom(point,placePoints[entry[0]]) : Infinity;
   return {entry,score:(Number.isFinite(distance) ? distance : 50) - sharedTags * 2};
 }).sort((a,b) => a.score - b.score).slice(0,3).map(item => item.entry);
 slot('related').innerHTML = cards(related);
 const screen = root.querySelector('[data-screen="detail"]');
 screen.classList.add('destination-trip');
 screen.replaceChildren(trip);
 const header = root.querySelector('header');
 const jumpMenu = screen.querySelector('.trip-jumps');
 const jumpLinks = [...jumpMenu.querySelectorAll('a:not([hidden])')];
 const jumpSections = jumpLinks.map(link => document.getElementById(link.hash.slice(1)));
 let activeLink = null;
 let scrollFrame = 0;
 function updateActiveSection(){
   scrollFrame = 0;
   const menuBounds = jumpMenu.getBoundingClientRect();
   const atPageEnd = window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 2;
   const activeIndex = atPageEnd ? jumpLinks.length - 1 : jumpSections.findLastIndex(
     section => section.getBoundingClientRect().top <= menuBounds.bottom + 24
   );
   const nextLink = jumpLinks[activeIndex] || null;
   if (nextLink === activeLink) return;
   activeLink?.removeAttribute('aria-current');
   activeLink = nextLink;
   if (!activeLink) return;
   activeLink.setAttribute('aria-current','location');
   const linkBounds = activeLink.getBoundingClientRect();
   const leftEdge = menuBounds.left + 5;
   const rightEdge = menuBounds.right - 5;
   if (linkBounds.left < leftEdge) jumpMenu.scrollLeft += linkBounds.left - leftEdge;
   if (linkBounds.right > rightEdge) jumpMenu.scrollLeft += linkBounds.right - rightEdge;
 }
 function scheduleActiveSection(){
   if (!scrollFrame) scrollFrame = requestAnimationFrame(updateActiveSection);
 }
 const updateHeaderHeight = () => {
   screen.style.setProperty('--trip-header-height',header.offsetHeight + 'px');
   scheduleActiveSection();
 };
 updateHeaderHeight();
 new ResizeObserver(updateHeaderHeight).observe(header);
 window.addEventListener('scroll',scheduleActiveSection,{passive:true});
 window.addEventListener('resize',scheduleActiveSection);
 const slider = screen.querySelector('[data-trip-slider]');
 if (slider && window.Swiper && slider.querySelectorAll('.swiper-slide').length > 1) {
   const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
   const timer = slider.querySelector('[data-trip-timer]');
   new window.Swiper(slider,{
     speed: reduceMotion ? 0 : 550,
     effect: 'fade',
     fadeEffect: {crossFade: true},
     autoplay: reduceMotion ? false : {delay: 6000,disableOnInteraction: true,pauseOnMouseEnter: true},
     pagination: {
       el: slider.querySelector('.trip-photo-dots'),
       clickable: true,
       bulletElement: 'button'
     },
     a11y: {paginationBulletMessage: details ? 'Show destination photo {{index}}' : 'Show garden photo {{index}}'},
     on: {
       autoplayStart() {
         timer.hidden = false;
       },
       autoplayStop() {
         timer.hidden = true;
       },
       slideChange() {
         timer.style.setProperty('--elapsed',0);
       },
       autoplayTimeLeft(_slider,_timeLeft,remaining) {
         timer.style.setProperty('--elapsed',Math.max(0,Math.min(1,1 - remaining)));
       }
     }
   });
 }
 document.title = name + ' — Ghumna Jaun';
}
function detail(){
 const [name,type,,,dining] = place;
 const destination = {
   "Kathmandu Fun Park": "Kathmandu Fun Park, Bhrikutimandap",
   "Natural History Museum": "Natural History Museum, Swayambhu, Kathmandu",
   "Jump KTM, Jhamsikhel": "Jump Jhamsikhel Indoor Trampoline Park, Lalitpur",
   "Whoopee Land": "Whoopee Land Amusement and Water Park, Chobhar",
   "Kathmandu Fun Valley": "Kathmandu Fun Valley, Palanse, Bhaktapur",
   "Bagh Bhairab Temple": "Bagh Bhairab Temple, Kirtipur",
   "Indrayani Temple (Luti Ajima)": "Lhuti Ajima, Kathmandu",
   "Mhepi Ajima Temple": "Mhepi Ajima Temple, Kathmandu",
   "Bijeshwari Temple": "Bijeshwari Temple, Kathmandu",
   "Santaneshwor Mahadev Temple": "Santaneshwor Mahadev Temple, Jharuwarasi",
   "Chonga Ganesh Temple": "Chonga Ganesh Temple, Sallaghari, Bhaktapur",
   "Surya Binayak Temple": "Surya Binayak Temple, Bhaktapur",
   "Tribhuvan Park": "Tribhuwan Park, Thankot",
   "Balaju Baisdhara Park": "Balaju Baisdhara Park, Kathmandu",
   "Manjushree Park": "Manjushree Park, Chobhar, Kirtipur",
   "Sangdaha Picnic Spot": "Sangdaha Picnic Spot, Changunarayan",
   'Changu Narayan Temple': 'Changu Narayan Temple, Bhaktapur',
   'Godawari Botanical Garden': 'National Botanical Garden, Godawari, Lalitpur',
   'Mahalaxmi Temple, Telkot': 'Shree Pancha Mahalaxmi Temple, Telkot, Bhaktapur',
   'White Gumba': 'Druk Amitabha Mountain Monastery, Nagarjun',
   'Chandragiri Hills': 'Chandragiri Hills Cable Car, Thankot',
   'Bheda Farm': 'Bheda Farm, Chitlang',
   '1,000 Steps, Dhulikhel': 'Dhulikhel 1000 Steps',
   'Central Zoo': 'Central Zoo, Jawalakhel',
   'Simulation Science Center': 'Simulation Science Center, Chobhar, Kirtipur',
   'National Museum of Nepal': 'National Museum of Nepal, Chhauni',
   'Adinath Lokeshwar Temple': 'Adinath Lokeshwar Temple, Chobhar',
   'Bajra Barahi Temple': 'Bajra Barahi Temple, Chapagaon',
   'Nil Barahi Temple': 'Nil Barahi Temple, Bode, Madhyapur Thimi',
   'Chandeshwori Temple': 'Chandeshwori Temple, Banepa',
   'Matsya Narayan Temple': 'Matsya Narayan Temple, Machhegaun',
   'Doleshwor Mahadev Temple': 'Doleshwor Mahadev Temple, Sipadol, Bhaktapur',
   'Ashapuri Mahadev Temple': 'Ashapuri Mahadev Temple, Suryabinayak, Bhaktapur',
   'Ichangu Narayan Temple': 'Ichangu Narayan Temple, Ichangu, Nagarjun',
   'Shikali Temple': 'Shikali Temple, Khokana',
   'Rudrayani Temple': 'Rudrayani Temple, Khokana',
   'Karya Binayak Temple': 'Karya Binayak Temple, Bungamati',
   'Rato Machhindranath Temple': 'Rato Machhindranath Temple, Bungamati',
   'Jal Binayak Temple': 'Jal Binayak Temple, Chobhar',
   'Sankhu & Bajrayogini': 'Bajrayogini Temple, Sankhu',
   'Four Binayak Ganesh Temples': 'Jal Binayak Temple, Chobhar'
 }[name] || name;
 const route = new URLSearchParams({
   api: '1',
   origin: (startingFrom === 'Kathmandu Valley' ? 'Kathmandu' : startingFrom) + ', Nepal',
   destination: destination + ', Nepal'
 });
 if (name === 'Sankhu & Bajrayogini') route.set('waypoints','Sankhu, Nepal');
 if (name === 'Four Binayak Ganesh Temples') {
   const temples = destinationGuides[name].stops;
   route.set('waypoints',temples.slice(0,-1).map(([temple]) => temple + ', Nepal').join('|'));
 }
 const directionsUrl = 'https://www.google.com/maps/dir/?' + route;
 renderTrip(name,destination,directionsUrl,type,dining);
}
const blogPosts = [
 {
   slug: 'nagarkot-sunrise',
   title: 'A day trip to Nagarkot for sunrise',
   category: 'Day trips',
   photo: 'Nagarkot',
   intro: 'Plan an early viewpoint stop, a slow breakfast, and an optional visit to Changu Narayan.',
   sections: [
     ['Start with the view', 'Pick a viewpoint before departure and check the forecast the night before. An early start gives you time to settle in before sunrise. Bring a warm layer for the ridge and plan breakfast after the viewpoint.'],
     ['Choose your second stop', 'Stay near Nagarkot for a short walk or continue to Changu Narayan. If you plan to walk the ridge route, arrange your return from the temple in advance.'],
     ['Plan the return', 'Confirm your pickup point and last return if you’re using public transport. Allow time for breakfast and the return journey when deciding whether to add a second stop.']
   ],
   places: ['Nagarkot','Changu Narayan Temple'],
   sources: [['Nepal Tourism Board’s Nagarkot sunrise guide','https://ntb.gov.np/nagarkot-sunrise-and-sunset']]
 },
 {
   slug: 'chitlang-markhu-kulekhani',
   title: 'Chitlang, Markhu and Kulekhani in a day',
   category: 'Weekend outings',
   photo: 'Kulekhani',
   intro: 'Pair a Chitlang village walk with lunch by the reservoir, and add Bheda Farm if time allows.',
   sections: [
     ['Morning in Chitlang', 'Start with a village walk and decide how much walking you want to do before lunch. Nepal Tourism Board describes a walking route from Chitlang toward the reservoir and a longer motor road. Plan transport around the route you choose.'],
     ['Lunch by the water', 'Use Markhu as your lakeside stop at Indra Sarowar. Leave time for lunch and a walk before starting the return leg. Confirm availability directly with a local operator if boating is part of your plan.'],
     ['Fit in Bheda Farm', 'Treat the farm as an optional stop around Chitlang and Markhu. Check the approach locally and leave enough time for the return, especially if your group wants a longer village walk.']
   ],
   places: ['Chitlang','Kulekhani','Bheda Farm'],
   sources: [
     ['Nepal Tourism Board’s Chitlang and Kulekhani route','https://ntb.gov.np/plan-your-trip/trip-ideas/chandragiri-chitlang-kulekhani'],
     ['Chitlang Organic Village Resort’s Bheda Farm guide','https://www.chitlang.com/bheda-farm-chitlang/']
   ]
 },
 {
   slug: 'family-day-trips-with-kids',
   title: 'Three Kathmandu Valley outings with kids',
   category: 'Family',
   photo: 'Godawari Botanical Garden',
   intro: 'Choose a garden walk in Godawari, a heritage stop in Bhaktapur, or a village outing in Chitlang.',
   sections: [
     ['Godawari for an outdoor morning', 'Choose a short walk through the botanical garden and set a turnaround point everyone can manage. Plan a snack break and check opening hours before setting off.'],
     ['Bhaktapur for a heritage walk', 'Start at Durbar Square, look for carved windows and courtyards, then stop for lunch. A short route with room for pauses makes it easier to adapt the day to younger children.'],
     ['Chitlang for a village day', 'Make the village the main stop and choose a lunch venue ahead of time. If the group has energy for another walk, decide on the day how much to add.']
   ],
   places: ['Godawari Botanical Garden','Bhaktapur Durbar Square','Chitlang'],
   sources: [['Nepal Tourism Board’s guide to travel with children','https://ntb.gov.np/en/plan-your-trip/trip-ideas/travel-with-children']]
 },
 {
   slug: 'sankhu-bajrayogini-day-trip',
   title: 'A heritage walk and temple visit in Sankhu',
   category: 'Dharmik',
   photo: 'Sankhu & Bajrayogini',
   intro: 'Walk through Sankhu before visiting Bajrayogini Temple on a single outing.',
   sections: [
     ['Begin in the town', 'Give yourself time to explore Sankhu on foot before heading to the temple. Start with a short loop and leave room for lunch or tea after the shrine visit.'],
     ['Continue to Bajrayogini', 'Bajrayogini is a pilgrimage site for Buddhist and Hindu devotees. Follow the directions from town, allow time for the uphill approach, and check access locally if your group needs an easier route.'],
     ['Choose a return point', 'If you’re meeting a driver or using public transport, agree on a return point before leaving town. The destination guide includes a map and directions with Sankhu and Bajrayogini as separate stops.']
   ],
   places: ['Sankhu & Bajrayogini'],
   sources: [['Nepal Tourism Board’s pilgrimage guide','https://trade.ntb.gov.np/tourist-destination/pilgrimage-sites-2/']]
 }
];

function renderBlogCards(posts){
 find('blog-posts').innerHTML = posts.map(post => {
   const photo = photos[post.photo];
   return `<div class="col" data-reveal="fade-up"><a class="place page-link blog-card" href="${pageUrl('post.html',{slug:post.slug})}" data-post="${post.slug}">
     <span class="art"><img src="${thumbnail(photo)}" data-original="${photo.src}" alt="" loading="lazy"></span>
     <span class="info"><span class="meta">${post.category}</span><strong>${post.title}</strong><span>${post.intro}</span><span class="meta">Read guide ↗</span></span>
   </a></div>`;
 }).join('');
}

function renderBlogPost(){
 const slug = query.get('slug');
 if (slug === 'four-binayak-ganesh-temples') {
   window.location.replace(pageUrl('destination.html',{place:'Four Binayak Ganesh Temples',category:'Dharmik'}));
   return;
 }
 const post = blogPosts.find(entry => entry.slug === slug);
 if (!post) {
   const title = query.has('slug') ? 'Guide not found' : 'Choose a travel guide';
   find('post-title').textContent = title;
   document.title = title + ' — Ghumna Jaun';
   return;
 }
 document.title = post.title + ' — Ghumna Jaun';
 find('post-title').textContent = post.title;
 find('post-category').textContent = post.category;
 find('post-intro').textContent = post.intro;
 const image = find('post-image');
 setPhoto(image,photos[post.photo]);
 image.hidden = false;
 find('post-body').innerHTML = post.sections.map(([heading,copy]) =>
   `<section data-reveal="fade-up"><h2>${heading}</h2><p>${copy}</p></section>`
 ).join('') + `<section data-reveal="fade-up"><h2>Destination guides</h2><div class="guide-links">${post.places.map(name =>
   `<a class="page-link" data-place="${name}" href="${pageUrl('destination.html',{place:name})}">${name} ↗</a>`
 ).join('')}</div></section><section class="blog-sources" data-reveal="fade-up"><h2>Sources</h2><ul>${post.sources.map(([label,url]) =>
   `<li><a href="${url}" target="_blank" rel="noopener">${label}</a></li>`
 ).join('')}</ul></section>`;
}

if (view === 'home') {
 renderBlogCards(blogPosts.slice(0,3));
 find('home-chips').innerHTML = chips(categories);
 const featured = ['Nagarkot','Panauti','Chitlang','Chandragiri Hills','Godawari Botanical Garden','Bhaktapur Durbar Square'];
 find('featured').innerHTML = cards(featured.map(name => places.find(entry => entry[0] === name)));
 find('family-trips').innerHTML = cards(familyTrips.map(name => places.find(entry => entry[0] === name)));
 renderCities();
 setPhoto(find('dining-image'),photos.Dining);
 startSelect.addEventListener('change',updateLinks);
} else if (view === 'blog') {
 renderBlogCards(blogPosts);
} else if (view === 'post') {
 renderBlogPost();
} else if (view === 'results') {
 results();
} else if (view === 'about') {
 setPhoto(find('about-image'),photos.Kulekhani);
} else if (view === 'contact') {
 const contactForm = find('contact-form');
 let sending = false;
 const sendContactMessage = async () => {
   if (sending) return;
   sending = true;
   const button = contactForm.querySelector('button[type="submit"]');
   const status = find('contact-status');
   const controller = new AbortController();
   const timeout = setTimeout(() => controller.abort(),20000);
   button.disabled = true;
   button.textContent = 'Sending…';
   contactForm.setAttribute('aria-busy','true');
   status.hidden = false;
   status.textContent = 'Sending your message…';
   delete status.dataset.state;
   try {
     const response = await fetch(contactForm.action,{
       method:'POST',body:new FormData(contactForm),
       headers:{'Accept':'application/json','X-GhumnaJaun-Contact':'1'},
       signal:controller.signal
     });
     const result = await response.json();
     if (!response.ok || result.ok !== true) {
       if (result.errors && window.jQuery?.fn.validate) {
         window.jQuery(contactForm).validate().showErrors(result.errors);
       }
       throw new Error(result.message || 'Your message could not be sent. Please try again later.');
     }
     contactForm.reset();
     if (window.jQuery?.fn.validate) window.jQuery(contactForm).validate().resetForm();
     find('contact-errors').hidden = true;
     status.dataset.state = 'success';
     status.textContent = result.message;
   } catch (error) {
     status.dataset.state = 'error';
     status.textContent = error.name === 'AbortError' ?
       'We could not confirm whether your message was sent. Please wait before trying again, or email hello@ghumnajaun.com.' :
       error instanceof SyntaxError || error instanceof TypeError ?
       'Your message could not be sent. Please try again later or email hello@ghumnajaun.com.' : error.message;
   } finally {
     clearTimeout(timeout);
     sending = false;
     button.disabled = false;
     button.textContent = 'Send message';
     contactForm.removeAttribute('aria-busy');
   }
 };
 if (window.jQuery?.fn.validate) {
   const summary = find('contact-errors');
   const trim = value => value.trim();
   window.jQuery(contactForm).validate({
     errorElement:'span',
     errorClass:'contact-field-error',
     rules:{
       name:{required:true,maxlength:100,normalizer:trim},
       email:{required:true,email:true,maxlength:254,normalizer:trim},
       subject:{required:true},
       body:{required:true,maxlength:3000,normalizer:trim}
     },
     messages:{
       name:{required:'Please enter your name.',maxlength:'Use 100 characters or fewer.'},
       email:{required:'Please enter your email address.',email:'Enter a valid email address.',maxlength:'Use 254 characters or fewer.'},
       subject:{required:'Please choose a topic.'},
       body:{required:'Please enter your message.',maxlength:'Use 3,000 characters or fewer.'}
     },
     highlight(element){element.setAttribute('aria-invalid','true');},
     unhighlight(element){element.removeAttribute('aria-invalid');},
     invalidHandler(){summary.hidden=false;},
     showErrors(){
       this.defaultShowErrors();
       const count = this.numberOfInvalids();
       summary.textContent = count ? `Please check ${count === 1 ? 'the highlighted field' : 'the ' + count + ' highlighted fields'}.` : '';
       if (!count) summary.hidden=true;
     },
     submitHandler(){
       summary.hidden=true;
       sendContactMessage();
       return false;
     }
   });
 } else {
   contactForm.addEventListener('submit',event => {
     event.preventDefault();
     if (contactForm.reportValidity()) sendContactMessage();
   });
 }
} else if (place) {
 detail();
} else if (view === 'detail') {
 root.querySelector('[data-screen="detail"]').hidden = true;
 find('destination-missing').hidden = false;
 document.title = 'Place not found — Ghumna Jaun';
}
updateLinks();
function setPhoto(image,photo,width){
 image.alt = photo.alt;
 image.dataset.original = photo.src;
 image.src = thumbnail(photo,width);
}
const bannerPlaces = ['Nagarkot','Chandragiri Hills','Panauti','Kulekhani','Bheda Farm'];
const bannerPhotos = {
 'Kulekhani': {
   src: 'https://upload.wikimedia.org/wikipedia/commons/f/fa/%E0%A4%95%E0%A5%81%E0%A4%B2%E0%A5%87%E0%A4%96%E0%A4%BE%E0%A4%A8%E0%A5%80_The_Kulekhani_Dam.jpg',
   alt: 'Aerial view of Kulekhani Reservoir',
   author: 'Prakash Gopali85',
   commons: 'https://commons.wikimedia.org/wiki/File:%E0%A4%95%E0%A5%81%E0%A4%B2%E0%A5%87%E0%A4%96%E0%A4%BE%E0%A4%A8%E0%A5%80_The_Kulekhani_Dam.jpg',
   license: '4.0'
 },
 'Bheda Farm': {
   src: 'https://exploredamanpalung.com/assets/images/veda-bheda-farm.jpg?v=52',
   alt: 'Bheda Farm pastures near Chitlang',
   author: 'Ganesh Bista / Explore Daman & Palung',
   source: 'https://exploredamanpalung.com/'
 }
};
if (view === 'home') find('banner-slides').innerHTML = bannerPlaces.map((name,index) => {
 const photo = bannerPhotos[name] || photos[name];
 return `<div class="swiper-slide"><img src="${thumbnail(photo)}" data-original="${photo.src}"${bannerPhotos[name] ? ` data-fallback="${photos[name].src}"` : ''} alt="" loading="${index === 0 ? 'eager' : 'lazy'}"></div>`;
}).join('');
const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
const bannerElement = find('banner');
const bannerTimer = find('banner-timer');
const banner = bannerElement && window.Swiper ? new window.Swiper(bannerElement, {
 rewind: true,
 effect: 'fade',
 fadeEffect: {crossFade: true},
 speed: motion.matches ? 0 : 550,
 autoplay: motion.matches ? false : {delay: 6000, disableOnInteraction: false},
 pagination: {el: bannerElement.querySelector('.banner-pagination'), clickable: true},
 a11y: {enabled: true, paginationBulletMessage: 'Show featured destination {{index}}'},
 on: {
   slideChange(slider) {
     const name = bannerPlaces[slider.realIndex];
     const label = find('banner-place');
     label.dataset.place = name;
     label.textContent = name + ' ↗';
     decorateArrows(label);
     label.href = pageUrl('destination.html',{place:name});
     bannerTimer.style.setProperty('--elapsed',0);
   },
   autoplayTimeLeft(_slider,_timeLeft,remaining) {
     bannerTimer.style.setProperty('--elapsed',Math.max(0,Math.min(1,1 - remaining)));
   }
 }
}) : null;
if (banner && !motion.matches) bannerTimer.hidden = false;
const aos = motion.matches ? null : window.AOS;
function refreshReveals(){
 if (!aos) return;
 root.querySelectorAll('[data-reveal]:not([data-aos])').forEach(element => {
   element.dataset.aos = element.dataset.reveal;
 });
 requestAnimationFrame(() => aos.refreshHard());
}
if (aos) {
 root.classList.add('aos-enabled');
 refreshReveals();
 aos.init({once: true, duration: 480, offset: 35, easing: 'ease-out-cubic', disableMutationObserver: true});
}

const creditList = find('credits');
if (creditList) creditList.innerHTML = [...Object.values(photos),...Object.values(bannerPhotos)].map(photo => {
 const license = photo.license === 'PD' ? 'Public domain' : photo.license === 'BY'
  ? '<a href="https://creativecommons.org/licenses/by/4.0/">CC BY 4.0</a>'
  : '<a href="https://creativecommons.org/licenses/by-sa/' + photo.license + '/">CC BY-SA ' + photo.license + '</a>';
 const attribution = photo.license ? `by ${photo.author} · ${license}` : `· Photo source: ${photo.author}`;
 return `<li>
   <span class="credit-thumbnail"><img src="${thumbnail(photo,120)}" data-original="${photo.src}" width="64" height="48" alt="" loading="lazy" decoding="async"></span>
   <div><a href="${photo.commons || photo.source}">${photo.alt}</a> ${attribution}</div>
 </li>`;
}).join('');
root.addEventListener('error', event => {
 const image = event.target;
 if (image.tagName !== 'IMG') return;
 const original = image.dataset.original;
 if (original && image.src !== original) {
   delete image.dataset.original;
   image.src = original;
 } else if (image.dataset.fallback && image.src !== image.dataset.fallback) {
   delete image.dataset.original;
   image.src = image.dataset.fallback;
   delete image.dataset.fallback;
 } else {
   image.hidden = true;
 }
},true);
decorateArrows(root);
initializeCookieConsent();
function decorateArrows(container){
 const icon = '<svg viewBox="0 0 24 24" focusable="false"><path d="M4 2h18v18h-4V8.8L4.8 22 2 19.2 15.2 6H4z"/></svg>';
 const walker = document.createTreeWalker(container,NodeFilter.SHOW_TEXT);
 const nodes = [];
 while (walker.nextNode()) {
   if (walker.currentNode.textContent.includes('↗')) nodes.push(walker.currentNode);
 }
 nodes.forEach(node => {
   if (node.textContent === '↗' && node.parentElement.classList.contains('link-arrow')) {
     node.parentElement.innerHTML = icon;
     return;
   }
   const fragment = document.createDocumentFragment();
   node.textContent.split('↗').forEach((part,index) => {
     if (index) {
       const arrow = document.createElement('span');
       arrow.className = 'link-arrow';
       arrow.setAttribute('aria-hidden','true');
       arrow.innerHTML = icon;
       fragment.append(arrow);
     }
     fragment.append(document.createTextNode(part));
   });
   node.replaceWith(fragment);
 });
}
function initializeCookieConsent(){
 const storageKey = 'ghumna-jaun-consent';
 const lifetime = 180 * 24 * 60 * 60 * 1000;
 function readChoice(){
   try {
     const saved = JSON.parse(localStorage.getItem(storageKey));
     if (saved?.version === 1 && typeof saved.embeds === 'boolean' &&
         Number.isFinite(saved.savedAt) && saved.savedAt <= Date.now() && Date.now() - saved.savedAt < lifetime) return saved;
   } catch {}
   return null;
 }
 let choice = readChoice();
 root.insertAdjacentHTML('beforeend',`
   <section class="cookie-banner" id="gj-cookie-banner" aria-labelledby="gj-cookie-title" hidden>
     <div class="cookie-banner-copy"><h2 id="gj-cookie-title">Your privacy choices</h2>
       <p>Optional maps and videos connect to OpenStreetMap and YouTube. Choose whether to load them. <a href="privacy-policy.html">Privacy Policy</a></p>
     </div>
     <div class="cookie-actions"><button type="button" data-embed-consent="reject">Reject</button><button type="button" data-embed-consent="accept">Accept</button></div>
   </section>
   <dialog class="search-dialog cookie-dialog" id="gj-cookie-dialog" aria-labelledby="gj-cookie-dialog-title">
     <div class="search-dialog-heading"><h2 id="gj-cookie-dialog-title">Cookie preferences</h2>
       <button class="search-close" type="button" id="gj-cookie-close" aria-label="Close cookie preferences"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
     </div>
     <p>Choose whether embedded OpenStreetMap maps and YouTube videos can load. These providers receive connection information and may use their own cookies or storage.</p>
     <p>Your theme preference is stored separately. Photos, fonts and site files also load from their providers. <a href="privacy-policy.html">Read our Privacy Policy</a>.</p>
     <p id="gj-cookie-current" role="status"></p>
     <div class="cookie-actions"><button type="button" data-embed-consent="reject" autofocus>Reject</button><button type="button" data-embed-consent="accept">Accept</button></div>
   </dialog>`);
 const banner = find('cookie-banner');
 const dialog = find('cookie-dialog');
 const bannerMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
 let preferencesAnimation = null;
 let preferencesClosing = false;
 function openPreferences(){
   if (dialog.open) return;
   dialog.showModal();
   if (!bannerMotion.matches) {
     preferencesAnimation = dialog.animate([{opacity:0},{opacity:1}],{duration:220,easing:'ease-out'});
   }
 }
 async function closePreferences(){
   if (!dialog.open || preferencesClosing) return;
   preferencesClosing = true;
   const opacity = getComputedStyle(dialog).opacity;
   preferencesAnimation?.cancel();
   if (!bannerMotion.matches) {
     dialog.classList.add('is-closing');
     preferencesAnimation = dialog.animate([{opacity},{opacity:0}],{duration:220,easing:'ease-out',fill:'forwards'});
     await preferencesAnimation.finished.catch(() => {});
   }
   dialog.close();
   dialog.classList.remove('is-closing');
   preferencesAnimation?.cancel();
   preferencesClosing = false;
 }
 let bannerTarget = false;
 let bannerTransition = 0;
 let bannerAnimation = null;
 const updateBannerSpace = () => root.style.setProperty('--cookie-banner-height',banner.hidden ? '0px' : banner.offsetHeight + 'px');
 async function updateBannerVisibility(){
   const show = choice === null;
   if (show === bannerTarget) return;
   bannerTarget = show;
   const transition = ++bannerTransition;
   const wasHidden = banner.hidden;
   const style = getComputedStyle(banner);
   const opacity = style.opacity;
   const transform = style.transform;
   bannerAnimation?.cancel();
   banner.hidden = false;
   banner.inert = !show;
   updateBannerSpace();
   if (!bannerMotion.matches) {
     const frames = show ? [
       {opacity:wasHidden ? 0 : opacity,transform:wasHidden ? 'translateY(100%)' : transform},
       {opacity:1,transform:'translateY(0)'}
     ] : [{opacity,transform},{opacity:0,transform}];
     bannerAnimation = banner.animate(frames,{
       duration:show ? 280 : 180,
       easing:'ease-out',
       fill:'forwards'
     });
     await bannerAnimation.finished.catch(() => {});
     if (transition !== bannerTransition) return;
   }
   banner.hidden = !show;
   banner.inert = false;
   bannerAnimation?.cancel();
   updateBannerSpace();
 }
 function applyChoice(){
   updateBannerVisibility();
   find('cookie-current').textContent = choice === null ? 'No choice saved yet.' :
     choice.embeds ? 'Current choice · Optional maps and videos accepted.' : 'Current choice · Optional maps and videos rejected.';
   root.querySelectorAll('.consent-embed').forEach(container => {
     const frame = container.querySelector('iframe[data-consent-src]');
     const allowed = choice?.embeds === true;
     container.querySelector('.consent-placeholder').hidden = allowed;
     frame.hidden = !allowed;
     if (allowed && !frame.hasAttribute('src')) frame.src = frame.dataset.consentSrc;
     if (!allowed) frame.removeAttribute('src');
   });
   updateBannerSpace();
 }
 root.querySelectorAll('[data-cookie-preferences]').forEach(button => {
   button.hidden = false;
   button.addEventListener('click',openPreferences);
 });
 root.querySelectorAll('[data-embed-consent]').forEach(button => button.addEventListener('click',() => {
   choice = {version:1,embeds:button.dataset.embedConsent === 'accept',savedAt:Date.now()};
   try { localStorage.setItem(storageKey,JSON.stringify(choice)); } catch {}
   applyChoice();
   closePreferences();
 }));
 find('cookie-close').addEventListener('click',closePreferences);
 dialog.addEventListener('cancel',event => {
   event.preventDefault();
   closePreferences();
 });
 dialog.addEventListener('click',event => {
   if (event.target !== dialog) return;
   const bounds = dialog.getBoundingClientRect();
   if (event.clientX < bounds.left || event.clientX > bounds.right ||
       event.clientY < bounds.top || event.clientY > bounds.bottom) closePreferences();
 });
 window.addEventListener('storage',event => {
   if (event.key === storageKey || event.key === null) {
     choice = readChoice();
     applyChoice();
   }
 });
 new ResizeObserver(updateBannerSpace).observe(banner);
 applyChoice();
}
})();
