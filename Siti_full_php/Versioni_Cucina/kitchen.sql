-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 27, 2025 alle 10:16
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kitchen`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `ricette`
--

CREATE TABLE `ricette` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `ingredienti` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `tempo_di_preparazione` int(11) DEFAULT NULL,
  `grado_di_difficolta` varchar(50) DEFAULT NULL,
  `clicks` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ricette`
--

INSERT INTO `ricette` (`id`, `nome`, `descrizione`, `ingredienti`, `image_url`, `tempo_di_preparazione`, `grado_di_difficolta`, `clicks`) VALUES
(1, 'Risotto ai Funghi Porcini', 'Il risotto ai funghi porcini è un piatto classico della cucina italiana, perfetto per ogni stagione, ma particolarmente apprezzato in autunno. Il sapore intenso dei funghi porcini si fonde con la cremosità del risotto, creando un piatto ricco e avvolgente. È ideale per una cena elegante o un pasto conviviale in famiglia.', '320 g di riso Arborio\r\n200 g di funghi porcini freschi (oppure secchi)\r\n1 litro di brodo vegetale\r\n1 cipolla piccola\r\n2 cucchiai di olio extravergine di oliva\r\n50 g di burro\r\n50 g di parmigiano grattugiato\r\n1 bicchiere di vino bianco secco\r\nSale e pepe q.b.\r\nPrezzemolo fresco (facoltativo)', 'https://www.cucchiaio.it/content/dam/cucchiaio/it/ricette/2013/12/ricetta-risotto-funghi-porcini-zafferano/risotto-porcini-zafferano-finale.jpg', 30, 'Media', 0),
(2, 'Risotto alla Milanese', 'Un classico risotto cremoso arricchito da zafferano, che gli conferisce un colore dorato e un sapore delicato.', '320g di riso Arborio\r\n1 cipolla piccola\r\n40g di burro\r\n1 bustina di zafferano\r\n1 litro di brodo di pollo\r\n50g di parmigiano grattugiato\r\n1 bicchiere di vino bianco', 'https://www.cucchiaio.it/content/dam/cucchiaio/it/ricette/2009/11/ricetta-risotto-alla-milanese/risotto-alla-milanese-ante.jpg', 25, 'Medio', 1),
(3, 'Pizza Margherita\r\n', 'La pizza simbolo della tradizione italiana, con pomodoro fresco, mozzarella e basilico.', '250g di farina\r\n150ml di acqua\r\n10g di lievito di birra\r\n200g di pomodoro pelato\r\n200g di mozzarella di bufala\r\nOlio d\'oliva, sale, basilico', 'https://cdn.shopify.com/s/files/1/0274/9503/9079/files/20220211142754-margherita-9920_5a73220e-4a1a-4d33-b38f-26e98e3cd986.jpg?v=1723650067', 45, 'Facile', 1),
(4, 'Pollo alla Cacciatora', 'Un piatto rustico con pollo cotto in un sugo ricco di pomodoro, olive e rosmarino.', '4 cosce di pollo\r\n200g di pomodorini\r\n100g di olive nere\r\n1 cipolla\r\n1 rametto di rosmarino\r\n1 bicchiere di vino bianco\r\nOlio d’oliva, sale, pepe', 'https://www.cucchiaio.it/content/cucchiaio/it/ricette/2009/12/ricetta-pollo-cacciatora/_jcr_content/header-par/image_single.img.jpg/1579020893122.jpg', 40, 'Medio', 0),
(5, 'Tiramisu', 'Il dessert italiano più famoso, con strati di savoiardi, crema al mascarpone e una spolverata di cacao amaro.', '250g di mascarpone\r\n100g di zucchero\r\n2 uova\r\n200g di savoiardi\r\nCaffè espresso\r\nCacao amaro in polvere', 'https://staticcookist.akamaized.net/wp-content/uploads/sites/21/2024/07/tiramisu-finale.jpeg', 20, 'Facile', 1),
(6, 'Vitello Tonnato', 'Un antipasto piemontese composto da fette di vitello servite con una salsa cremosa al tonno.', '800g di vitello\r\n200g di tonno sott’olio\r\n1 cucchiaio di capperi\r\n2 uova\r\n100ml di maionese\r\nLimone, sale, pepe', 'https://www.giallozafferano.it/images/176-17697/Vitello-tonnato_650x433_wm.jpg', 40, 'Medio', 0),
(7, 'Ramen Giapponese con Brodo di Maiale', 'Un piatto tradizionale giapponese composto da un brodo ricco e complesso di maiale, noodles fatti in casa, uova marinate, e topping di carne, verdure e alga nori.', '500g di ossa di maiale\r\n300g di pancetta di maiale\r\n100g di funghi shiitake\r\n1 cipolla\r\n2 spicchi d’aglio\r\n2 cucchiai di miso bianco\r\n4 uova\r\n300g di noodles per ramen\r\nSalsa di soia, mirin', 'https://blog.giallozafferano.it/cookingdada/wp-content/uploads/2020/11/IMG_20190530_180200-scaled.jpg', 300, 'Difficile', 0),
(8, 'Boeuf Bourguignon', 'Un piatto francese classico, un brasato di manzo cotto lentamente in vino rosso con cipolline, funghi e pancetta, fino a ottenere una carne tenerissima e un sugo ricco', '1 kg di carne di manzo per brasato\r\n750 ml di vino rosso Bourgogne\r\n200g di pancetta\r\n300g di cipolline\r\n200g di funghi champignon\r\n2 carote\r\n1 cipolla\r\n2 cucchiai di concentrato di pomodoro\r\nBrodo di carne', 'https://www.giallozafferano.it/images/25-2565/Boeuf-bourguignon_650x433_wm.jpg', 360, 'Difficile', 0),
(9, 'Tarte Tatin', 'Una torta rovesciata francese con mele caramellate e pasta sfoglia, preparata con la tecnica della cottura in padella prima della cottura finale in forno.', '6 mele Golden Delicious\r\n100g di zucchero\r\n50g di burro\r\n1 rotolo di pasta sfoglia\r\nSucco di limone', 'https://files.meilleurduchef.com/mdc/photo/ricetta/tarte-tatin-ricetta/tarte-tatin-ricetta-1200.jpg', 120, 'Difficile', 0),
(10, 'Risotto al Tartufo Bianco con Crema di Parmigiano Reggiano', 'Un risotto raffinato preparato con riso carnaroli, brodo vegetale, e un\'intensa crema di parmigiano, il tutto esaltato dal pregiato tartufo bianco grattugiato.', '320g di riso Carnaroli\r\n150g di parmigiano Reggiano\r\n50g di burro\r\n1 cipolla\r\n1 litro di brodo vegetale\r\n1 tartufo bianco fresco\r\n1 bicchiere di vino bianco secco', 'https://www.debic.com/sites/default/files/recipe/01020000-ac10-0242-c130-08d7d25c7da3.jpg', 90, 'Difficile', 0),
(11, 'Soufflé al Formaggio', 'Un piatto elegante e sofisticato, il soufflé al formaggio è preparato con una base di besciamella e formaggio grattugiato, perfetto per essere servito come antipasto o piatto principale.', '200g di formaggio grana o emmental\r\n60g di burro\r\n60g di farina\r\n500ml di latte\r\n4 uova\r\nSale, pepe, noce moscata', 'https://www.salepepe.it/files/2014/07/il-souffle-al-formaggio-step.jpg', 60, 'Difficile', 1),
(12, 'Cappuccino di Mare', 'Un antipasto sofisticato che gioca con le consistenze e i sapori, composto da una crema di pesce arricchita con una spuma di latte e un infuso di brodo di pesce.', '200g di pesce bianco (merluzzo o nasello)\r\n100g di gamberi\r\n1 l di brodo di pesce\r\n200ml di panna fresca\r\n1 cucchiaio di cognac\r\n50g di burro\r\nSale, pepe, erba cipollina', 'https://assets.gazzettadelsud.it/2021/03/aa793a31-fae1-48bc-b65d-baba3144c083.jpg', 180, 'Difficile', 0),
(13, 'Pasticcio di Maccheroni alla Siciliana', 'Un piatto ricco e complesso che combina pasta, carne, uova, formaggi e un sugo denso, tutto cotto in forno per un risultato irresistibile.', '400g di maccheroni\r\n300g di carne macinata di manzo e maiale\r\n200g di piselli\r\n4 uova sode\r\n200g di caciocavallo grattugiato\r\n500ml di sugo di pomodoro\r\n100g di pangrattato\r\n1 bicchiere di vino rosso', 'https://www.cucchiaio.it/content/cucchiaio/it/ricette/2013/11/ricetta-pasticcio-maccheroni-ragu-besciamella/_jcr_content/header-par/image_single.img.jpg/1427889458236.jpg', 180, 'Difficile', 0),
(14, 'Bavarese alla Vaniglia con Coulis di Lampone', 'Un dessert delicato e sofisticato, composto da una base di crema bavarese alla vaniglia con un\'intensa salsa di lamponi freschi.', '500ml di panna fresca\r\n250ml di latte\r\n100g di zucchero\r\n2 baccelli di vaniglia\r\n4 tuorli d’uovo\r\n10g di gelatina\r\n200g di lamponi\r\n100g di zucchero per la coulis', 'https://blog.giallozafferano.it/aryblue/wp-content/uploads/2014/04/Bavarese-vaniglia-lamponi.jpg', 240, 'Difficile', 0),
(15, 'Spaghetti Aglio, Olio e Peperoncino', 'Un piatto iconico della cucina italiana, semplice ma ricco di sapore, preparato con aglio, olio d\'oliva e peperoncino.', '200g di spaghetti\r\n3 spicchi di aglio\r\n2 peperoncini freschi o secchi\r\n50ml di olio extravergine d\'oliva\r\nSale e prezzemolo fresco', 'https://oliofarchioni.com/wp-content/uploads/2022/08/spaghetti-aglio-olio-e-peperoncino-1.jpg', 15, 'Facile', 0),
(16, 'Caprese', 'Un piatto fresco e semplice, perfetto come antipasto o piatto estivo, a base di pomodori, mozzarella e basilico.', '3 pomodori maturi\r\n200g di mozzarella di bufala\r\nFoglie di basilico fresco\r\nOlio extravergine d\'oliva\r\nSale e pepe', 'https://media-assets.lacucinaitaliana.it/photos/64b938d160df211dd0c14782/16:9/w_5760,h_3240,c_limit/shutterstock_508377580.jpg', 10, 'Facile', 2),
(17, 'Pasta alla Carbonara', 'Un piatto ricco e veloce, con un sugo a base di uova, guanciale e pecorino, perfetto per un pranzo sostanzioso.', '200g di pasta (spaghetti o rigatoni)\r\n100g di guanciale\r\n2 uova\r\n50g di pecorino romano grattugiato\r\nSale e pepe', 'https://www.giallozafferano.it/images/219-21928/Spaghetti-alla-Carbonara_650x433_wm.jpg', 20, 'Facile', 1),
(18, 'Bruschetta al Pomodoro', 'Un antipasto semplice e fresco con pane tostato, pomodori, aglio, basilico e olio d\'oliva.', '1 baguette o pane casereccio\r\n4 pomodori maturi\r\n2 spicchi d\'aglio\r\nBasilico fresco\r\nOlio extravergine d\'oliva\r\n', 'https://www.cucchiaio.it/content/cucchiaio/it/ricette/2009/11/ricetta-bruschetta-pomodoro/jcr:content/imagePreview.img10.jpg/1596697514993.jpg', 10, 'Facile', 1),
(19, 'Vellutata di Zucca', 'Un piatto autunnale e confortante, con la dolcezza della zucca e la cremosità della vellutata.', '800g di zucca\r\n1 cipolla\r\n500ml di brodo vegetale\r\n50ml di panna fresca\r\nSale e pepe', 'https://www.cucchiaio.it/content/dam/cucchiaio/it/ricette/2022/10/vellutata-di-zucca-e-patate/vellutata-di-zucca-e-patate-ante.jpg', 40, 'Facile', 1),
(20, 'Lasagna alla Bolognese', 'La Lasagna alla Bolognese è un piatto iconico della cucina italiana, composto da strati di pasta fresca, ricca ragù alla bolognese, besciamella e una generosa spolverata di parmigiano.', '12 sfoglie di lasagna (fresche o secche)\r\n200 g di parmigiano grattugiato (o grana padano\r\nRagù alla bolognese \r\nBesciamella', 'https://www.manjoo.it/wp-content/uploads/large_mol/1468842599_482_img.jpg', 90, 'Medio', 0),
(21, 'Tacos al Pastor', 'Un piatto tipico messicano, con carne di maiale marinata e cucinata su uno spiedo verticale, servita in tortillas.', '500g di carne di maiale (spalla o lonza)\r\n2 ananas\r\n2 cipolle\r\n2 peperoncini dried guajillo\r\n2 spicchi d\'aglio\r\n1 cucchiaino di cumino\r\nSucco di 2 limoni\r\nTortillas di mais\r\nSale e pepe', 'https://www.seriouseats.com/thmb/4kbwN13BlZnZ3EywrtG2AzCKuYs=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/20210712-tacos-al-pastor-melissa-hom-seriouseats-37-f72cdd02c9574bceb1eef1c8a23b76ed.jpg', 60, 'Media', 0),
(22, 'Fish and Chips', 'Un piatto classico inglese di pesce impanato e fritto, accompagnato da patatine fritte.', '500g di filetti di merluzzo\r\n250g di farina\r\n1 uovo\r\n300ml di birra fredda\r\n1 kg di patate\r\nSale e pepe', 'https://www.thespruceeats.com/thmb/sdVTq0h7xZvJjPr6bE2fhh5M3NI=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/SES-best-fish-and-chips-recipe-434856-hero-01-27d8b57008414972822b866609d0af9b.jpg', 40, 'Facile', 0),
(23, 'Biryani ', 'Un piatto speziato a base di riso, carne (pollo, agnello o manzo) e spezie, molto popolare in India e in molti paesi asiatici.', '300g di riso basmati\r\n500g di pollo\r\n2 cipolle\r\n1 cucchiaino di curcuma\r\n1 cucchiaino di cumino\r\n1 cucchiaino di garam masala\r\n2 pomodori\r\n1/2 tazza di yogurt\r\nSale e pepe', 'https://www.cucinaconalbert.com/wp-content/uploads/2021/04/63-biryani-di-pollo--868x1300.jpg', 90, 'Alta', 1),
(24, 'Croque Monsieur ', 'Un panino francese farcito con prosciutto e formaggio, poi grigliato e ricoperto con besciamella.', '8 fette di pane in cassetta\r\n200g di prosciutto cotto\r\n200g di formaggio Gruyère\r\n50g di burro\r\n2 cucchiai di farina\r\n250ml di latte\r\nSale e pepe', 'https://www.giallozafferano.it/images/221-22178/Croque-monsieur_450x300.jpg', 30, 'Facile', 0),
(25, 'Paella ', 'Un piatto tradizionale spagnolo originario di Valencia, preparato con riso, zafferano, carne e frutti di mare.', '300g di riso\r\n500g di frutti di mare (gamberi, cozze)\r\n1 pollo intero tagliato a pezzi\r\n1 cipolla\r\n1 peperone rosso\r\n2 pomodori\r\n1 cucchiaino di zafferano\r\n1 litro di brodo di pesce\r\nOlio d\'oliva', 'https://media-assets.lacucinaitaliana.it/photos/631e3d7dfd22adc9da924e51/4:3/w_1331,h_998,c_limit/Paella-all\'italiana.jpg', 60, 'Media', 0),
(26, 'Pasta al pesto di ortiche e noci pecan', 'Un\'alternativa al classico pesto, dove le ortiche, ricche di nutrienti, si mescolano con il sapore delicato delle noci pecan.', '200 g di pasta\r\n100 g di ortiche fresche\r\n50 g di noci pecan\r\n50 g di parmigiano grattugiato\r\nOlio d’oliva, sale, aglio', 'https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgTGXb3xJZWpBNGmmdkFXHQggoGpEGLT4le1snV7JqBxQcEbgWtecG-mZaJKNj4nuM2_ZRUjG3Y_-F5q3Aki8cXFXw-L03Qv4bhzZz-PVQwjYDCWeFGgEiJuy6pTBbZq08FOhm1bo8Khx8/s1600/IMG_5541.jpg', 20, 'Medio', 0),
(27, 'Zuppa fredda di pomodoro verde e cetriolo con menta', 'Un\'interpretazione fresca della classica zuppa di pomodoro, con l’aggiunta di cetriolo e menta per un gusto originale e rinfrescante.', '500 g di pomodori verdi\r\n1 cetriolo\r\n1 mazzetto di menta fresca\r\n1 cipolla rossa\r\nOlio d\'oliva, sale, pepe', 'https://www.cucchiaio.it/content/cucchiaio/it/ricette/2020/05/gazpacho-di-cetrioli-e-avocado-alla-menta/_jcr_content/header-par/image-single.img.jpg/1629529543098.jpg', 15, 'Facile', 0),
(28, '', 'Un’alternativa vegana alla frittata classica, fatta con farina di ceci, arricchita dal sapore della curcuma e del rosmarino.', '150 g di farina di ceci\r\n1 cucchiaino di curcuma\r\n1 rametto di rosmarino\r\n1 cipolla\r\nAcqua, sale, pepe', 'https://www.basilicosecco.com/wp-content/uploads/2024/07/Frittata-di-ceci.jpg', 25, 'Facile', 0),
(29, 'Insalata di rucola, arance e mandorle tostate', 'Un\'insalata fresca e leggera, che abbina la rucola piccante con la dolcezza delle arance e il croccante delle mandorle tostate.', '150 g di rucola\r\n2 arance\r\n50 g di mandorle\r\n1 cucchiaio di miele\r\nOlio d’oliva, sale, pepe', 'https://www.olioextraverginediolivasicilia.com/wp-content/uploads/2024/07/Insalata-estiva-con-mandorle-sgusciate-biologiche-e-miele-di-arancio-biologico.webp', 15, 'Facile', 0),
(30, 'Risotto al radicchio, noci e taleggio', 'Un risotto che unisce il sapore amarognolo del radicchio con la cremosità del taleggio e il croccante delle noci, perfetto per l\'autunno.', '250 g di riso Carnaroli\r\n150 g di radicchio\r\n100 g di taleggio\r\n30 g di noci\r\n1 cipolla, brodo vegetale, burro', 'https://www.giallozafferano.it/images/271-27140/Risotto-al-radicchio-e-taleggio_650x433_wm.jpg', 30, 'Medio', 0),
(31, ' Pollo al forno con mele e cipolle caramellate', 'Un piatto unico dal sapore equilibrato, con il pollo cotto al forno insieme a mele e cipolle caramellate per un contrasto dolce-salato.', '4 cosce di pollo\r\n2 mele\r\n2 cipolle rosse\r\n1 cucchiaio di zucchero di canna\r\nRosmarino, olio d’oliva, sale, pepe', 'https://machetiseimangiato.com/wp-content/uploads/2015/03/pollo-e-mele-lato.jpg', 45, 'Facile', 1),
(32, 'Tagliatelle al nero di seppia con crema di piselli e menta', 'Un primo piatto dal contrasto cromatico e di sapore, con le tagliatelle al nero di seppia abbinate a una crema fresca di piselli e menta.', '250 g di tagliatelle al nero di seppia\r\n200 g di piselli freschi o surgelati\r\n1 mazzetto di menta fresca\r\n1 cipolla\r\nOlio d’oliva, sale, pepe', 'https://cleaver.cue.rsi.ch/public/la1/programmi/intrattenimento/filo-diretto/ricette/290503-bg994z-Spaghettini-al-nero-di-seppia.jpeg/alternates/r16x9/290503-bg994z-Spaghettini-al-nero-di-seppia.jpeg', 20, 'Medio', 0),
(33, 'Tartare di salmone e avocado con salsa di soia al limone', 'Un antipasto fresco e leggero, con il salmone crudo mescolato all’avocado cremoso e condito con una salsa leggera di soia e limone.', '200 g di salmone fresco\r\n1 avocado\r\n2 cucchiai di salsa di soia\r\nSucco di 1 limone\r\nSemi di sesamo, pepe rosa', 'https://blog.giallozafferano.it/cucinoperpassione/wp-content/uploads/2019/08/Tartare-di-salmone-e-avocado.jpg', 15, 'Facile', 0),
(34, 'Polpette di lenticchie e zucca con salsa al pomodoro speziata', 'Polpette vegane a base di lenticchie e zucca, servite con una salsa di pomodoro arricchita da spezie che esaltano il sapore autunnale.', '150 g di lenticchie cotte\r\n200 g di zucca\r\n1 cipolla\r\n200 g di passata di pomodoro\r\nCurry, cumino, paprika', 'https://www.viridea.it/wp-content/uploads/2019/06/polpettine-al-sugo-con-lenticchie-e-semi-di-zucca-e1510757335166.jpg', 35, 'Medio', 0),
(35, 'Frittata di zucchine e fiori di zucca con ricotta e menta', 'Una frittata estiva e leggera, con zucchine e fiori di zucca freschi, arricchita dal sapore fresco della ricotta e della menta.', '2 zucchine\r\n6 fiori di zucca\r\n100 g di ricotta\r\n4 uova\r\nMenta fresca, sale, pepe', 'https://blog.giallozafferano.it/pelledipollo/wp-content/uploads/2023/07/frittata_fiorizucca1.jpg', 20, 'Facile', 1),
(36, 'Gnocchi di patate e barbabietola con burro nocciola e rosmarino', 'Gnocchi morbidi e colorati, a base di patate e barbabietola, serviti con un burro nocciola profumato al rosmarino per un piatto dal gusto delicato e ricco.', '500 g di patate\r\n200 g di barbabietola cotta\r\n150 g di farina\r\n1 uovo\r\n100 g di burro\r\n2 rametti di rosmarino\r\nSale e pepe', 'https://blog.giallozafferano.it/crisemaxincucina/wp-content/uploads/2017/12/Gnocchi-di-barbabietola-e-patate-al-burro-e-nocciole-4.jpeg', 40, 'Medio', 0),
(37, 'Tortino di lenticchie e melanzane', 'Un piatto vegetariano che unisce la consistenza ricca delle lenticchie con la delicatezza delle melanzane, perfetto come secondo piatto o piatto unico.', '200 g di lenticchie secche\r\n1 melanzana grande\r\n1 cipolla\r\n1 spicchio d\'aglio\r\n1 uovo\r\n50 g di pangrattato\r\n50 g di parmigiano grattugiato\r\nOlio extravergine d\'oliva q.b.\r\nSale e pepe q.b.\r\nUn rametto di rosmarino', 'https://blog.giallozafferano.it/unacamerieraincucina2701/wp-content/uploads/2023/06/Polpette-di-lenticchie-e-melanzane-senza-uova.jpg', 40, 'Medio', 0),
(38, 'Zuppa di zucca e tamarindo', 'Una zuppa dal sapore esotico, con il dolce della zucca che si fonde perfettamente con l\'acidulo del tamarindo, ideale per l\'inverno.', '500 g di zucca\r\n1 cipolla\r\n2 cucchiai di polpa di tamarindo\r\n1 litro di brodo vegetale\r\n1 cucchiaio di olio di cocco\r\nSale e pepe q.b.\r\nSemi di zucca tostati (opzionale, per guarnire)', 'https://i0.wp.com/ifeelbetta.com/wp-content/uploads/2019/10/104959913.jpg?resize=1024%2C1024&ssl=1', 30, 'Facile', 0),
(39, 'Crostata di fichi e gorgonzola', 'Una crostata salata che abbina la dolcezza dei fichi con il gusto deciso del gorgonzola. Perfetta per un antipasto o una cena leggera.', '1 rotolo di pasta brisée\r\n150 g di gorgonzola dolce\r\n200 g di fichi freschi\r\n1 cucchiaio di miele\r\nNoci tritate q.b.\r\nSale e pepe q.b.', 'https://merendedafavola.com/templates/yootheme/cache/Torta-salata.ai.fichi-e-gorgonzola-Merende-da-Favola-c135b838.jpeg', 25, 'Facile', 0),
(40, 'Riso nero con gamberi e cacao amaro', 'Un piatto originale che mescola il riso venere con il cacao amaro, creando un contrasto interessante con i gamberi, per un primo piatto raffinato e insolito.', '200 g di riso venere\r\n300 g di gamberi sgusciati\r\n2 cucchiaini di cacao amaro in polvere\r\n1 cipolla\r\n1 spicchio d\'aglio\r\n1 cucchiaio di olio extravergine d\'oliva\r\nBrodo vegetale q.b.\r\nSale e pepe q.b.\r\nPrezzemolo fresco per guarnire', 'https://blog.giallozafferano.it/creandosiimpara/wp-content/uploads/2023/02/curry-di-gamberi-2-scaled.jpeg', 35, 'Medio', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `email`, `username`, `password`) VALUES
(1, 'gianni@libero.it', 'Gianni', '$2y$10$aZmoD6EnrOFzTJr9UURm5OrHeL8xVlS5kB5yo9Hza7OIUt7V.TZ9q'),
(3, 'riccardomestre17@gmail.com', 'riccardo', '$2y$10$VNGVa5h55iNvaiUTiDhvTemZb9hioQ5W47hlZV2eyQ0digO6lkQ7q');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `ricette`
--
ALTER TABLE `ricette`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `ricette`
--
ALTER TABLE `ricette`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
