-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-06-18 16:20:11
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `color`
--

-- --------------------------------------------------------

--
-- 資料表結構 `analysis_records`
--

CREATE TABLE `analysis_records` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `analysis_result` longtext NOT NULL,
  `photo_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `analysis_records`
--

INSERT INTO `analysis_records` (`id`, `username`, `analysis_result`, `photo_count`, `created_at`) VALUES
(1, 'a1133377', '## 1. 照片條件觀察\n- 照片數量：1 張（搭配多張局部特寫輔助判斷）\n- 可用角度：清晰正面視角，略帶側身，有助於觀察臉部輪廓與五官細節。\n- 光線狀況：室內光線，整體畫面偏暗且帶有輕微顆粒感，背景光線偏冷調，但右側有暖色木質背景。\n- 是否可能影響膚色判斷：是的，照片整體色調偏冷且光線不均勻，可能輕微影響膚色冷暖調與明暗度的精準判斷。\n- 本次分析基準：主要以臉部五官最清晰、膚色受陰影影響較小的區域作為判斷依據。\n- 準確度提醒：由於僅有一張照片且光線條件較為複雜，本次分析的準確度可能會受到角度、表情與光線的影響，建議未來可提供多角度、自然光線下的照片以獲得更精確的分析。\n\n## 2. 臉型分析\n- 可能臉型：主要偏向**鵝蛋臉**，次要可能為**瓜子臉**。\n- 臉部長寬比例觀察：臉部長度與寬度比例適中，呈現柔和的橢圓形狀。\n- 額頭寬度觀察：額頭寬度看起來與顴骨、下顎線條大致協調。\n- 顴骨位置觀察：顴骨位置適中，線條柔和，沒有明顯突出感。\n- 下顎線條觀察：下顎線條收斂柔和，沒有明顯的稜角，過渡自然。\n- 下巴形狀觀察：下巴線條圓潤，略微尖收，但不過於銳利。\n- 判斷原因：臉部線條流暢，額頭、顴骨、下顎的寬度比例和諧，整體呈現圓潤而不過於寬闊的橢圓形狀，符合鵝蛋臉的特徵。下巴略微收尖，使其也帶有瓜子臉的秀氣感。\n- 臉型優勢：鵝蛋臉是公認最理想的臉型之一，擁有和諧的比例，幾乎適合所有髮型與妝容，能夠輕鬆駕馭多種風格。\n- 臉型修飾方向：由於臉型本身已非常和諧，修飾重點會放在凸顯五官優勢，而非大幅度改變臉型。可透過髮型與妝容強調臉部線條的立體感與精緻度。\n\n## 3. 五官特徵分析\n- 眉眼特色：眉毛自然，眉骨略為突出，為眼部增添立體感。眼睛偏大，眼型圓潤，眼尾略微上揚，臥蠶明顯，眼神看起來清澈而帶有幾分靈動感。瞳孔顏色在照片中呈現較淺的灰色或藍色，可能為瞳孔變色片效果，使眼神更為突出。\n- 眼型與眼神風格：圓潤的眼型結合上揚的眼尾，為眼神帶來甜美與一絲嫵媚的平衡，眼神清澈，整體風格偏向**甜美靈動**中帶有**清冷感**。\n- 鼻部特徵：鼻子鼻樑挺拔，鼻頭小巧精緻，鼻翼寬度適中，為臉部帶來立體感。\n- 唇部特徵：唇形飽滿，唇峰不明顯但線條柔和，唇色在照片中看起來自然偏淡。\n- 臉部留白比例：臉部留白比例適中，五官分佈協調，不會顯得過於擁擠或空曠。\n- 五官集中度：五官看起來比較集中於臉部中央，中庭比例適中。\n- 整體氣質：整體氣質偏向**精緻、溫柔**中帶有**清麗脫俗**的感覺，眼神是亮點，為整體增添了靈動感。\n- 最適合強調的五官：**眼睛**。\n- 妝容重點原因：大而圓潤的眼型搭配清澈的眼神，是臉部最具特色的部分，透過眼妝的加強能進一步放大其魅力，展現靈動或精緻的眼神風格。\n\n## 4. 個人氣質與風格定位\n- 主要風格定位：**清冷高級風**、**精緻千金感**\n- 次要風格定位：**韓系透明感**、**日系自然感**\n- 適合關鍵字：清麗、靈動、精緻、溫柔、脫俗、高雅\n- 不太適合的風格：過於狂野、強勢的歐美立體感，或是過於濃郁的港風濃顏感，這可能會掩蓋您本身清麗脫俗的氣質。\n- 判斷原因：您的五官精緻，尤其眼睛清澈有神，整體臉型和諧，散發出一種不食人間煙火的清冷感與高級感。同時，臉部線條柔和，也帶有溫柔與精緻的氣質，很適合走精緻千金路線。若妝容清淡，也能呈現韓系與日系的透明自然感。\n\n## 5. 膚色與色彩分析\n- 膚色明暗度：膚色看起來偏向**白皙**。\n- 膚色冷暖調：在照片光線下，膚色看起來偏向**冷調**或**中性偏冷**。\n- 膚色飽和度：膚色看起來飽和度適中，沒有明顯泛黃或泛紅。\n- 膚色乾淨度/透亮感：膚色看起來比較**乾淨透亮**。\n- 適合色彩明度：適合**中高明度**的色彩，能襯托膚色的白皙與透亮感。\n- 適合色彩飽和度：適合**中低飽和度**或**中高飽和度**的色彩，但應避免過於暗沉或過於螢光的顏色。\n- 適合色彩冷暖：由於膚色偏冷，建議選擇**冷色調**或**中性色調**的色彩，能更好地提升氣色，使膚色顯得更為白皙。\n- 判斷依據：照片中膚色在白色的毛領與深色的和服襯托下，呈現出較為白皙且帶有冷調的質感。手部膚色也顯示出冷調傾向。瞳孔顏色（若為真實）也常與冷色調膚色相關。\n- 光線誤差提醒：由於照片整體光線偏冷且略暗，可能使膚色看起來比實際更冷白。建議在自然光下再次確認膚色冷暖調。\n\n## 6. 四季色彩推測\n- 可能季節類型：**冬季冷色型 (Cool Winter)** 或 **冬季高對比型 (Deep Winter)**。\n- 次要可能季節：夏季冷色型 (Cool Summer)。\n- 季節色彩特徵：\n    - **冬季冷色型**：膚色偏冷，髮色深，瞳孔深邃，適合純粹、鮮明、明亮且冷調的色彩，對比度高。\n    - **冬季高對比型**：膚色偏冷或中性，髮色深，瞳孔深邃，適合濃郁、深邃、飽和度高的色彩，對比度強烈。\n- 適合的色彩方向：純粹的冷色調、高飽和度的深色、明亮的冷色、高對比度的搭配。例如：寶石藍、酒紅色、翠綠色、純白色、黑色、銀灰色。\n- 不適合的色彩方向：過於溫暖的橘色系、大地色系，以及過於柔和、低飽和度的模糊色，這些顏色可能讓膚色顯得暗沉或缺乏精神。\n- 判斷原因：您的膚色白皙偏冷，髮色深黑，瞳孔顏色清澈（即使是變色片也常選擇冷色調），與深色和服及鮮豔的紅色髮飾搭配時，能很好地駕馭高對比度與純粹的色彩，整體呈現出鮮明、清冷的特質，這與冬季色彩類型的特徵高度吻合。\n- 可信度：可信度約 80%。\n\n## 7. 專屬命定色票卡\n[COLOR:#1E3D59|午夜藍] [COLOR:#D04848|櫻桃紅] [COLOR:#F5E8C7|米白色] [COLOR:#5C5C5C|煙灰棕] [COLOR:#2C3E50|深海藍]\n\n## 8. 修容與打亮建議\n- 修容位置：\n    - **髮際線兩側與額角**：輕掃少量陰影，柔化額頭邊緣，使臉型看起來更為精緻。\n    - **顴骨下方凹陷處**：從耳中往嘴角方向斜掃，但範圍不宜過大，以提升顴骨的立體感，同時修飾臉部線條。\n    - **下顎線輕微帶過**：由於下顎線條本身已很柔和，只需在下顎骨邊緣輕輕帶過，強調輪廓清晰度即可。\n- 打亮位置：\n    - **眉骨下方**：提亮眉骨，使眉眼更具立體感。\n    - **眼下三角區**：提亮此區域能讓眼神更明亮，視覺上提升蘋果肌飽滿度。\n    - **鼻樑中段**：在鼻樑中段輕輕打亮，增加鼻子的挺拔感，但避免延伸至鼻頭，以保持鼻頭的精緻小巧。\n    - **唇峰**：輕點唇峰，使唇形更為立體飽滿。\n- 腮紅位置：\n    - **蘋果肌斜上方**：從蘋果肌中央向太陽穴方向斜向上輕掃，能拉長臉部視覺比例，同時提升氣色。\n    - **中庭較長者**：若有中庭偏長的困擾，可將腮紅位置略微橫向擴展至鼻翼兩側，有助於縮短視覺比例。\n- 鼻影位置：\n    - **眉頭下方至鼻翼兩側**：從眉頭下方順著眼窩凹陷處，輕輕向鼻翼兩側帶過，強調鼻樑立體感，並使眼窩更深邃。鼻頭處可輕微V字形修飾，讓鼻頭更顯精緻。\n- 下顎修飾：由於您的下顎線條柔和，修飾重點在於維持其流暢感。避免過度修容，以免造成不自然的陰影或讓臉部線條顯得過於銳利。\n- 為什麼這樣修容：這樣的修容方式能充分利用您臉型和諧的優勢，透過陰影與高光的對比，提升五官的立體度與精緻感，讓清麗的五官更加突出，同時保持整體妝容的輕透感。\n- 建議避免的修容方式：避免在顴骨下方大範圍、重手地修容，以免顯得臉部凹陷或疲憊。鼻影也應避免過重，以免顯得生硬不自然。\n\n## 9. 妝容建議與產品推薦\n\n- 底妝：\n  適合方向：追求清透自然、服貼持久的妝效，提升膚色均勻度與透亮感。\n  推薦：**YSL 恆久完美無瑕粉底液 BR10**（約 NT$2500）[BUY:https://www.google.com/search?tbm=shop&q=YSL+恆久完美無瑕粉底液+BR10]\n  適合原因：這款粉底液妝感輕薄卻有足夠遮瑕力，能打造出自然光澤的奶油肌，色號BR10為冷調白皙色，能很好地襯托您的膚色，提升整體透明感。\n\n- 眼影：\n  適合方向：選擇冷調或中性色系的眼影，以提升眼部深邃度與清澈感，可嘗試帶微閃的珠光色。\n  推薦：**dasique 九宮格眼影盤 #05 Sunset Muhly**（約 NT$980）[BUY:https://www.google.com/search?tbm=shop&q=dasique+九宮格眼影盤+#05+Sunset+Muhly]\n  適合原因：這盤眼影以柔和的粉棕色系為主，帶有細膩的珠光，能打造溫柔又深邃的眼妝，不會過於濃重，非常適合您的清麗氣質，其中的冷調粉色系也能襯托冷調膚色。\n\n- 唇彩：\n  適合方向：選擇冷調粉色、豆沙色或帶有藍調的紅色系唇彩，提升氣色同時不失清冷感。\n  推薦：**rom&nd 絕美任霧奶油唇釉 #07 FUSCHIA CUSHION**（約 NT$350）[BUY:https://www.google.com/search?tbm=shop&q=rom%26nd+絕美任霧奶油唇釉+#07+FUSCHIA+CUSHION]\n  適合原因：這款唇釉的色號#07是帶有藍調的冷粉色，能有效提亮冷調膚色，顯白顯氣色。質地輕盈，能打造自然柔霧的唇妝，符合您的清冷高級感。\n\n- 腮紅：\n  適合方向：選擇冷調粉色、乾燥玫瑰色或帶紫調的藕粉色腮紅，營造自然好氣色。\n  推薦：**CANMAKE 巧麗腮紅組 PW38**（約 NT$280）[BUY:https://www.google.com/search?tbm=shop&q=CANMAKE+巧麗腮紅組+PW38]\n  適合原因：PW38是經典的梅子色，帶有冷調的粉紫，非常適合冷調膚色，輕掃在蘋果肌上能呈現自然紅潤感，提升氣色而不顯黃。\n\n- 眉彩/眼線：\n  適合方向：選擇與髮色相近的深棕色或灰黑色眉筆，強調眉型自然流暢。眼線則建議選擇棕色或黑色，打造精緻眼神。\n  推薦：**KATE 零瑕疵立體眉彩筆N BR-3 自然棕**（約 NT$330）[BUY:https://www.google.com/search?tbm=shop&q=KATE+零瑕疵立體眉彩筆N+BR-3+自然棕]\n  適合原因：KATE眉筆筆芯軟硬適中，顏色自然，BR-3是帶灰調的自然棕色，能與深髮色協調，畫出自然立暢的眉型。\n\n## 10. 日常妝容步驟\n- Step 1 底妝：取適量清透型粉底液，用美妝蛋或刷具均勻塗抹全臉，以輕拍方式上妝，確保服貼自然。針對黑眼圈或局部瑕疵，可再局部點塗遮瑕膏，並輕輕拍開。\n- Step 2 眉毛：使用眉筆勾勒出自然眉型，順著眉毛生長方向填補空隙，眉頭輕掃帶過，眉尾稍稍拉長，使眉型俐落有神。\n- Step 3 眼妝：選擇淺冷棕色或藕粉色眼影（例如dasique #05的淺色），大範圍暈染眼窩打底。再用稍深一階的同色系眼影加強眼尾與眼下，增加深邃感。最後用深棕色眼線筆勾勒內眼線，眼尾可依眼型稍稍平拉或略微上揚，增加眼神精緻度。刷上纖長型睫毛膏，讓眼睛更顯有神。\n- Step 4 腮紅：選用冷調粉色腮紅（如CANMAKE PW38），輕輕掃在蘋果肌斜上方，少量多次疊加，營造自然紅潤的好氣色。\n- Step 5 唇彩：塗抹冷調豆沙色或自然粉色唇釉（如rom&nd #07），輕點於唇中央再向外暈染，打造自然漸層感，或均勻塗抹呈現飽滿唇色。\n- Step 6 定妝：用透明蜜粉輕掃全臉，特別是T字部位，吸附多餘油脂，使妝容持久清爽。\n- 妝容完成後的整體效果：整體妝容清透自然，五官精緻度提升，眼神靈動有神，氣色紅潤，散發出溫柔而清麗的日常魅力。\n\n## 11. 約會/正式場合妝容建議\n- 妝容風格：**精緻優雅，帶有清冷魅惑感**。\n- 眼妝加強方式：\n    - 在日常眼妝的基礎上，可疊加帶有細閃的珠光眼影於眼皮中央和臥蠶處，增加眼妝的閃耀度。\n    - 眼線可稍微拉長並微微上揚，營造更具魅惑感的眼型。\n    - 選擇纖長濃密型的睫毛膏，或黏貼局部假睫毛，放大雙眼。\n    - 可在眼尾使用少量帶有酒紅或深紫色調的眼影，增加眼妝的層次感與成熟韻味。\n- 唇彩加強方式：\n    - 選擇飽和度更高、帶有光澤感的冷調紅色系（如櫻桃紅 #D04848 或酒紅色 #8B0000）唇膏或唇釉，精準勾勒唇形，打造豐滿飽和的唇妝，提升氣場。\n    - 亦可嘗試帶有漿果色調的唇彩，更添神秘與高貴感。\n- 修容與打亮：\n    - 修容可稍微加重，加強顴骨下方、髮際線與下顎線的陰影，使臉部輪廓更加立體分明。\n    - 打亮則可選擇帶有細緻光澤的產品，提亮蘋果肌、鼻樑、眉骨和唇峰，讓臉部在燈光下更顯精緻透亮。\n- 適合場合：約會、晚宴、正式聚會、藝術展覽、節慶活動。\n- 為什麼適合：這款妝容在維持您清麗氣質的基礎上，透過眼妝和唇彩的加強，提升了整體妝容的精緻度與吸睛度，使您在特殊場合中既能展現優雅，又不失個人獨特的清冷魅力。\n\n## 12. 髮色與髮型建議\n- 推薦髮色 1：**冷棕色** #6F4E37。此髮色帶有灰調，能襯托冷調膚色，使膚色更顯白皙，同時增添溫柔氣質，不會像純黑髮那樣過於沉重，又能保持髮色的深邃感。\n- 推薦髮色 2：**藍黑色** #2F4F4F。這是一種帶有藍調的深黑色，在陽光下會呈現低調的藍光，非常適合冷冬型人，能極大程度地提升膚色的白皙度與透明感，顯得髮質光澤感好，更具高級感。\n- 推薦髮色 3：**酒紅色** #8B0000。飽和度較高的酒紅色，能為冷調膚色帶來活力與暖意，同時展現出獨特的時尚感與氣場，與您的精緻氣質相得益彰。\n- 建議避免髮色：過於明亮的金黃色 #FFD700、橘色 #FFA500 或暖調的亞麻色 #E0B388，這些顏色可能與您的冷調膚色產生衝突，讓膚色顯得暗沉或蠟黃。\n- 適合髮型長度：中長髮至長髮。您的臉型優勢可以駕馭不同長度，但中長髮或長髮更能展現溫柔優雅的氣質。\n- 適合瀏海：\n    - **空氣瀏海/八字瀏海**：能柔化臉部線條，增添甜美與溫柔感，同時修飾額頭。\n    - **中分/旁分無瀏海**：若想展現更成熟、俐落的風格，中分或大旁分能拉長臉部線條，凸顯五官的精緻度。\n- 適合捲度：\n    - **大波浪捲/S型捲**：能增加髮量感，營造浪漫溫柔的氛圍，同時修飾臉部線條，讓臉型看起來更為柔和。\n    - **髮尾微捲**：若不喜歡大捲，髮尾輕微的內彎或外翹也能增添活潑感與時尚度。\n- 原因：深色髮色能襯托冷調膚色，提升白皙度與質感。髮型上則以柔和線條為主，透過捲度或瀏海修飾，平衡臉部比例，並展現您獨有的精緻溫柔氣質。\n\n## 13. 穿搭色彩建議\n- 穿搭方案 1：\n  上身：**純白色** #FFFFFF 真絲襯衫，質地柔軟，帶有自然光澤。\n  下身：**深海藍** #2C3E50 高腰西裝闊腿褲，剪裁流暢，垂墜感佳。\n  外套：**煙灰棕** #5C5C5C 輕薄款長版風衣外套，增添層次感與知性美。\n  鞋包：**黑色** #000000 尖頭低跟鞋，搭配**午夜藍** #1E3D59 鍊條小方包。\n  主色調：**冷調中性色系** #FFFFFF #2C3E50 #5C5C5C\n  適合原因：這套穿搭以冷調中性色為主，純白色襯衫提亮膚色，深海藍與煙灰棕帶來知性與高級感，整體風格簡約卻不失質感，非常適合您的清冷高級氣質。\n\n- 穿搭方案 2：\n  上身：**藕粉色** #D7B9D5 羊絨針織衫，柔軟親膚，帶有溫柔氣息。\n  下身：**銀灰色** #C0C0C0 緞面A字裙，材質輕盈，微光澤感。\n  外套：**深灰色** #696969 羊毛短版西裝外套，提升俐落感。\n  鞋包：**裸粉色** #F8C8DC 尖頭瑪麗珍鞋，搭配**櫻桃紅** #D04848 迷你手提包。\n  主色調：**柔和冷粉與灰調** #D7B9D5 #C0C0C0 #D04848\n  適合原因：這套穿搭以柔和的冷調粉與灰為主，藕粉色與銀灰色能襯托膚色的白皙與溫柔，櫻桃紅小包點綴其中，提升亮點與時尚度，展現您精緻甜美又不失優雅的風格。\n\n## 14. 飾品與配件建議\n- 適合金屬色：**銀飾** #C0C0C0、**白K金** #F0F0F0、**鉑金** #E5E4E2。這些冷色調金屬能與您的冷調膚色協調，襯托出膚色的白皙與透亮感。\n- 適合飾品材質：珍珠 #F5F5DC、透明感水晶或鋯石 #E0FFFF、輕透壓克力、細緻的純銀或白K金飾品。\n- 適合飾品大小：精緻小巧或中等大小的飾品。過於粗獷或誇張的飾品可能壓過您清麗的氣質。\n- 適合眼鏡框色：**銀色** #C0C0C0、**黑色** #000000、**深藍色** #1A2B3C、**透明色** #FFFFFF (半透明)。\n- 適合包包色：**午夜藍** #1E3D59、**酒紅色** #8B0000、**煙灰棕** #5C5C5C、**純白色** #FFFFFF。\n- 適合鞋款色：**黑色** #000000、**裸色** #D8A28C (偏冷調或中性)、**深藍色** #1A2B3C、**銀灰色** #C0C0C0。\n- 原因：選擇冷色調金屬和清透材質的飾品，能更好地與您的膚色和氣質搭配，提升整體精緻度。飾品大小以不搶走五官風采為原則。包包和鞋款則選擇與服裝主色調和諧的冷色系，或作為亮點的飽和冷色，都能為整體造型加分。\n\n## 15. 建議避免的顏色與風格\n- 妝容地雷色：\n    - 眼影：過於暖調的橘色 #FFA500、土黃色 #C2B280，可能讓眼妝看起來顯髒或使膚色顯黃。\n    - 唇彩：螢光橘 #FFBF00、過於暖調的珊瑚橘 #FF7F50，可能讓唇色與膚色不協調，顯得氣色不佳。\n    - 腮紅：暖調的橘色 #FFA500，容易讓臉部顯黃或妝感浮誇。\n- 穿搭地雷色：\n    - 過於明亮或螢光的暖色調，如螢光綠 #39FF14、螢光黃 #CCFF00，可能使膚色顯得暗沉或氣色被壓暗。\n    - 過於飽和且暖調的土橘色 #D2691E、芥末黃 #C4B454，可能讓整體造型顯得不夠精緻，甚至顯老。\n- 髮色地雷色：\n    - 明亮的金黃色 #FFD700、橘紅色 #FF4500，可能使膚色顯得蠟黃或缺乏質感。\n- 不建議妝容風格：\n    - **濃郁歐美妝**：過於強調高光陰影、厚重眼線和誇張唇色，可能掩蓋您清麗的五官特色，顯得不自然。\n    - **煙燻妝**：若顏色選擇不當（如暖棕色），可能讓眼妝顯得沉重，不夠清透。\n- 不建議穿搭風格：\n    - **過度堆疊的街頭風格**：可能與您精緻溫柔的氣質不符，顯得臃腫或不協調。\n    - **過於甜膩的可愛風**：可能讓您看起來不夠成熟，未能充分展現清冷高級感。\n- 原因：這些顏色和風格可能與您的冷調膚色和精緻氣質產生衝突，導致顯黃、顯灰、顯髒、氣色被壓暗、五官變模糊、臉部比例被放大、妝感不協調等問題，未能凸顯您本身的優勢。\n\n## 16. 總結\n- 最適合的整體風格：**清冷精緻的優雅風格**。\n- 最推薦的妝容方向：強調**清澈靈動的眼妝**，搭配**冷調柔和的唇頰色彩**，打造通透自然的妝感。\n- 最推薦的穿搭色系：以**冷色調、中性色系**為主，適度點綴**純粹飽和的亮色**，如櫻桃紅 #D04848、午夜藍 #1E3D59。\n- 最推薦的髮色：**冷棕色** #6F4E37 或 **藍黑色** #2F4F4F。\n- 一句鼓勵總結：親愛的您，您的美獨具一格，擁有和諧精緻的臉龐與清澈靈動的眼神。願這些色彩與風格的建議，能成為您探索自我風格旅程中的靈感，讓您的獨特魅力，如清晨的露珠般，閃耀著自信與光芒！', 1, '2026-06-13 08:06:46');

-- --------------------------------------------------------

--
-- 資料表結構 `celebrity_analysis`
--

CREATE TABLE `celebrity_analysis` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `celebrity_analysis`
--

INSERT INTO `celebrity_analysis` (`id`, `name`, `image_path`, `content`, `created_at`) VALUES
(9, '田柾國', '[\"uploads/celebrities/1778228107_69fd9b8bacc39.jpg\",\"uploads/celebrities/1778228107_69fd9b8bad564.jpg\",\"uploads/celebrities/1778228107_69fd9b8badc76.jpg\"]', '## 1. 色彩類型\n- 色彩季型：深冬 (Deep Winter)\n- 適合色調：冷調、深邃、飽和\n- 關鍵色彩：純黑、寶藍、祖母綠、深紫紅\n\n## 2. 妝容方向\n- 底妝：自然霧面或微光澤，修飾膚色不均\n- 眼妝：簡潔眼線、大地色或灰棕色系眼影、強調睫毛\n- 腮紅：裸粉色、冷調玫瑰色，輕掃於顴骨\n- 唇彩：漿果色、磚紅色、深豆沙色，可選微霧或水潤質地\n- 整體妝感：清爽、俐落、強調眼神與唇部色彩\n\n## 3. 推薦色票\n[COLOR:#000000|純黑色]\n[COLOR:#1E90FF|寶藍色]\n[COLOR:#8A2BE2|紫羅蘭色]\n[COLOR:#DC143C|緋紅色]\n[COLOR:#2F4F4F|深板岩灰]\n\n## 4. 風格關鍵字\n- 風格：街頭潮流、酷感簡約、率性休閒\n- 適合穿搭：皮革外套、連帽上衣、寬鬆剪裁、丹寧單品、金屬飾品\n- 適合髮色：烏黑色、深棕色、藍黑色', '2026-05-08 08:15:21'),
(10, '金泰亨', '[\"uploads/celebrities/1778228145_69fd9bb167ae9.jpg\",\"uploads/celebrities/1778228145_69fd9bb167f48.jpg\",\"uploads/celebrities/1778228145_69fd9bb168355.jpg\"]', '## 1. 色彩類型\n- 色彩季型：深冬 (Deep Winter)\n- 適合色調：清晰、飽和、冷調、深邃\n- 關鍵色彩：純黑、深藍、寶石紅、翠綠\n\n## 2. 妝容方向\n- 底妝：輕薄霧面或自然光澤，修飾膚色不均\n- 眼妝：眉毛清晰有型，眼線俐落或微煙燻，深棕、灰黑色系眼影\n- 腮紅：極簡、自然修容或淡雅玫瑰色\n- 唇彩：裸粉、玫瑰豆沙、酒紅、磚紅\n- 整體妝感：精緻、沉穩、有質感\n\n## 3. 推薦色票\n[COLOR:#000000|純黑]\n[COLOR:#9B111E|寶石紅]\n[COLOR:#008080|翠綠]\n[COLOR:#000080|皇家藍]\n[COLOR:#FFFFFF|純白]\n\n## 4. 風格關鍵字\n- 風格：精緻、沉穩、俐落、時尚\n- 適合穿搭：剪裁俐落的西裝、高質感面料、簡約設計、對比色搭配\n- 適合髮色：自然黑、深棕、冷調深灰', '2026-05-08 08:15:55'),
(11, '朴智旻', '[\"uploads/celebrities/1778228178_69fd9bd2523b2.jpg\",\"uploads/celebrities/1778228178_69fd9bd25292c.jpg\",\"uploads/celebrities/1778228178_69fd9bd253117.jpg\"]', '## 1. 色彩類型\n- 色彩季型：冷冬 (True Winter)\n- 適合色調：清晰、飽和、偏冷的色調\n- 關鍵色彩：黑、白、皇家藍、莓果紅、冰粉\n\n## 2. 妝容方向\n- 底妝：輕薄透亮，自然光澤\n- 眼妝：簡潔眼線、大地冷棕、銀灰色系、或無眼影強調睫毛\n- 腮紅：冷粉色、淡紫色系、或修容為主\n- 唇彩：莓果色、冷調玫瑰、裸粉、正紅色\n- 整體妝感：精緻、清冷、簡約時尚\n\n## 3. 推薦色票', '2026-05-08 08:16:45'),
(12, '鄭號錫', '[\"uploads/celebrities/1778228248_69fd9c18a5e38.jpg\",\"uploads/celebrities/1778228248_69fd9c18a6331.jpg\",\"uploads/celebrities/1778228248_69fd9c18a6ce1.jpg\"]', '## 1. 色彩類型\n- 色彩季型：暖春 (Warm Spring)\n- 適合色調：暖色調、明亮、清澈\n- 關鍵色彩：暖棕色、米色、暖橘色、金黃色\n\n## 2. 妝容方向\n- 底妝：輕薄、自然光澤、暖米色調\n- 眼妝：大地色系、暖棕色眼線、自然睫毛\n- 腮紅：珊瑚橘、杏桃色\n- 唇彩：暖橘、蜜桃粉、裸棕色\n- 整體妝感：清爽、活力、自然\n\n## 3. 推薦色票\n[COLOR:#E0CDB6|米色]\n[COLOR:#FF7F50|珊瑚橘]\n[COLOR:#FFD700|金黃色]\n[COLOR:#808000|橄欖綠]\n[COLOR:#8B4513|暖棕色]\n\n## 4. 風格關鍵字\n- 風格：街頭時尚、活力、陽光、潮流\n- 適合穿搭：寬鬆剪裁、運動休閒、層次感、金屬飾品\n- 適合髮色：暖棕色、深咖啡色、自然黑', '2026-05-08 08:17:40'),
(13, '金南俊', '[\"uploads/celebrities/1778228383_69fd9c9feecd3.jpg\",\"uploads/celebrities/1778228383_69fd9c9fef281.jpg\",\"uploads/celebrities/1778228383_69fd9c9fef622.jpg\"]', '## 1. 色彩類型\n- 色彩季型：冬季型\n- 適合色調：冷色調、清晰色調、深色調\n- 關鍵色彩：黑色、深藍色、銀灰色\n\n## 2. 妝容方向\n- 底妝：霧面或半霧面、貼合膚色\n- 眼妝：簡潔深邃、大地色系或灰色系、強調眼線\n- 腮紅：裸粉色、灰粉色、輕掃於顴骨\n- 唇彩：裸色、玫瑰豆沙色、自然唇色\n- 整體妝感：清爽俐落、沉穩有型\n\n## 3. 推薦色票\n[COLOR:#000000|純黑色]\n[COLOR:#0A1128|海軍藍]\n[COLOR:#8C92AC|石板灰]\n[COLOR:#008060|祖母綠]\n[COLOR:#A1045A|深玫瑰紅]\n\n## 4. 風格關鍵字\n- 風格：沉穩、都會、潮流、簡約\n- 適合穿搭：俐落剪裁、質感面料、黑白灰單品、皮革\n- 適合髮色：霧灰棕、銀白色、黑色、深藍色', '2026-05-08 08:19:56'),
(14, '閔玧其', '[\"uploads/celebrities/1778228702_69fd9dde12146.jpg\",\"uploads/celebrities/1778228702_69fd9dde1299b.jpg\",\"uploads/celebrities/1778228702_69fd9dde12dcc.jpg\"]', '## 1. 色彩類型\n- 色彩季型：深冬 (Deep Winter)\n- 適合色調：清晰、飽和、冷調\n- 關鍵色彩：純黑、純白、寶石藍、酒紅\n\n## 2. 妝容方向\n- 底妝：輕薄霧面、自然遮瑕\n- 眼妝：簡潔線條、深色眼線、冷棕或灰調眼影\n- 腮紅：裸粉、冷調玫瑰色\n- 唇彩：漿果色、冷調紅、乾燥玫瑰\n- 整體妝感：精緻、清爽、強調輪廓\n\n## 3. 推薦色票\n請提供 5 個適合色彩。\n\n[COLOR:#000000|純黑]\n[COLOR:#FFFFFF|純白]\n[COLOR:#0000CD|寶石藍]\n[COLOR:#800020|酒紅]\n[COLOR:#36454F|深灰]\n\n## 4. 風格關鍵字\n- 風格：簡約、都會、俐落、個性\n- 適合穿搭：高對比、質感面料、經典款、設計感單品\n- 適合髮色：烏黑、深棕、冷調灰、白金', '2026-05-08 08:25:13'),
(15, '金碩珍', '[\"uploads/celebrities/1778244074_69fdd9ea66130.jpg\",\"uploads/celebrities/1778244074_69fdd9ea66576.jpg\",\"uploads/celebrities/1778244074_69fdd9ea669a9.jpg\"]', '## 1. 色彩類型\n- 色彩季型：冬季型\n- 適合色調：冷色調、純色、高飽和色\n- 關鍵色彩：黑色、純白色、寶石藍、酒紅色\n\n## 2. 妝容方向\n- 底妝：霧面或微霧光，膚色均勻清透\n- 眼妝：簡潔內眼線或細緻外眼線，少量冷調眼影，睫毛根根分明\n- 腮紅：淡雅冷粉色或裸粉色，輕掃於顴骨\n- 唇彩：玫瑰豆沙色、冷調紅棕色、漿果色\n- 整體妝感：精緻、清爽、強調輪廓\n\n## 3. 推薦色票\n[COLOR:#000000|純黑色]\n[COLOR:#FFFFFF|純白色]\n[COLOR:#0F52BA|寶石藍]\n[COLOR:#B76E79|玫瑰豆沙色]\n[COLOR:#800020|酒紅色]\n\n## 4. 風格關鍵字\n- 風格：精緻、都會、簡約、優雅、沉穩\n- 適合穿搭：剪裁俐落的西裝、襯衫、皮衣，單色或高對比配色\n- 適合髮色：烏黑色、深棕色、藍黑色', '2026-05-08 12:41:24');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `address` varchar(255) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `total_amount` int(11) NOT NULL DEFAULT 0,
  `status` varchar(30) NOT NULL DEFAULT '已成立',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `username`, `customer_name`, `phone`, `address`, `payment_method`, `total_amount`, `status`, `created_at`) VALUES
(1, 'a1133377', 'a1133377', '0999999999', '高雄市', '貨到付款', 1450, '已成立', '2026-06-18 13:11:38');

-- --------------------------------------------------------

--
-- 資料表結構 `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`) VALUES
(1, 1, 1, 'Dior Addict Lip Glow 潤唇膏', 1450, 1, 1450);

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `stock`, `is_active`, `created_at`) VALUES
(1, 'Dior Addict Lip Glow 潤唇膏', 'Dior 經典潤色護唇膏，適合日常淡妝與自然妝感，可增加唇部氣色。', 1450, 'https://placehold.co/600x400/fff0f6/c94f7c?text=Dior+Lip+Glow', 19, 1, '2026-06-18 13:01:54'),
(2, 'YSL 情挑誘光水唇膏', 'YSL 熱門唇膏系列，質地滋潤，適合打造光澤感唇妝。', 1500, 'https://placehold.co/600x400/fde8ef/b94a95?text=YSL+Lipstick', 18, 1, '2026-06-18 13:01:54'),
(3, 'MAC 子彈唇膏 Ruby Woo', 'MAC 經典霧面紅唇色，適合正式妝容、復古妝感與氣場穿搭。', 850, 'https://placehold.co/600x400/ffe4ef/c94f7c?text=MAC+Ruby+Woo', 25, 1, '2026-06-18 13:01:54'),
(4, 'NARS Radiant Creamy Concealer 遮瑕蜜', 'NARS 熱門遮瑕產品，可修飾黑眼圈、痘疤與局部暗沉，妝感自然。', 1200, 'https://placehold.co/600x400/f8efff/b94a95?text=NARS+Concealer', 15, 1, '2026-06-18 13:01:54'),
(5, 'Maybelline Fit Me 反孔特霧粉底液', 'Maybelline 開架熱銷粉底液，適合油肌與混合肌，妝感偏霧面。', 450, 'https://placehold.co/600x400/fff7fa/c94f7c?text=Maybelline+Fit+Me', 35, 1, '2026-06-18 13:01:54'),
(6, 'L\'Oréal Paris Infallible 持久粉底液', 'L\'Oréal Paris 熱門底妝商品，主打持妝與遮瑕，適合日常通勤妝。', 650, 'https://placehold.co/600x400/e8ddff/b94a95?text=LOreal+Foundation', 28, 1, '2026-06-18 13:01:54'),
(7, 'rom&nd 果汁唇釉 Juicy Lasting Tint', '韓國 rom&nd 人氣唇釉，玻璃唇妝感明顯，適合甜美與韓系妝容。', 350, 'https://placehold.co/600x400/ffdbe8/c94f7c?text=romand+Tint', 40, 1, '2026-06-18 13:01:54'),
(8, '3CE Blur Water Tint 霧面水唇釉', '3CE 熱門唇彩，質地輕薄，適合柔霧感妝容與韓系穿搭。', 590, 'https://placehold.co/600x400/fff0f6/b94a95?text=3CE+Tint', 30, 1, '2026-06-18 13:01:54'),
(9, 'CLIO Kill Cover Mesh Glow Cushion 氣墊粉餅', 'CLIO 人氣氣墊粉餅，妝感帶光澤，適合想要快速完成底妝的使用者。', 790, 'https://placehold.co/600x400/f8efff/c94f7c?text=CLIO+Cushion', 22, 1, '2026-06-18 13:01:54'),
(10, 'CANMAKE Cream Cheek 霜狀腮紅', 'CANMAKE 經典開架腮紅，質地服貼，適合打造自然紅潤氣色。', 320, 'https://placehold.co/600x400/ffe4ef/b94a95?text=CANMAKE+Cheek', 32, 1, '2026-06-18 13:01:54'),
(11, 'ETUDE Fixing Tint 霧面唇釉', 'ETUDE 熱門韓系唇釉，妝感柔霧，適合日常妝與學生族群。', 420, 'https://placehold.co/600x400/fff7fa/c94f7c?text=ETUDE+Fixing+Tint', 26, 1, '2026-06-18 13:01:54'),
(12, 'peripera Ink Mood Glowy Tint 唇釉', 'peripera 人氣光澤唇釉，顏色活潑，適合甜美、清新與韓系妝容。', 360, 'https://placehold.co/600x400/ffdbe8/b94a95?text=peripera+Tint', 38, 1, '2026-06-18 13:01:54');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(1, 'a1133377', '$2y$10$.2Mg4qfcgszI70kK6Hs4TuS3PhilMbP0gSNHK.PASTt2iarKWRoS6', 'user', '2026-06-13 07:13:51');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `analysis_records`
--
ALTER TABLE `analysis_records`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `celebrity_analysis`
--
ALTER TABLE `celebrity_analysis`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- 資料表索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `analysis_records`
--
ALTER TABLE `analysis_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `celebrity_analysis`
--
ALTER TABLE `celebrity_analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
