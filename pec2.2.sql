cd ./Documents/PEC2
wget http://geneontology.org/gene-associations/goa_human.gaf.gz
wget http://geneontology.org/gene-associations/gene_association.mgi.gz
wget http://geneontology.org/gene-associations/gene_association.zfin.gz
wget http://geneontology.org/gene-associations/gene_association.fb.gz

zcat goa_human.gaf.gz | grep -v "\!" > goa_human.txt
zcat gene_association.mgi.gz | grep -v "\!" > goa_mouse.txt
zcat gene_association.zfin.gz | grep -v "\!" > goa_fish.txt
zcat gene_association.fb.gz | grep -v "\!" > goa_fly.txt

mysql -u vfanjul -p
uoc
USE genomes;

CREATE TABLE gene_ontology
(id varchar(50) NOT NULL,
function varchar(300) NOT NULL,
PRIMARY KEY (id));
LOAD DATA LOCAL INFILE 'gene_ontology.dat' INTO TABLE gene_ontology;
SELECT * FROM gene_ontology WHERE id = 'GO:0003700' OR id = 'GO:0007254' OR id = 'GO:0000380' OR id = 'GO:0001501';
-- SELECT * FROM gene_ontology LIMIT 10;



CREATE TABLE human (
DB varchar(50) NOT NULL,
DB_Object_ID varchar(50) NOT NULL,
Gene varchar(50) NOT NULL,
Qualifier varchar(50) NOT NULL,
id varchar(50) NOT NULL,
DB_Reference varchar(50) NOT NULL,
Evidence_Code varchar(50) NOT NULL,
With_From varchar(50) NOT NULL,
Aspect varchar(50) NOT NULL,
DB_Object_Name varchar(300) NOT NULL,
DB_Object_Synonym varchar(50) NOT NULL,
DB_Object_Type varchar(50) NOT NULL,
Taxon varchar(50) NOT NULL,
Dat varchar(50) NOT NULL,
Assigned_By varchar(50) NOT NULL,
Annotation_Extension varchar(50) NOT NULL,
Gene_Product_Form_ID varchar(50) NOT NULL,
PRIMARY KEY (Gene,id),
FOREIGN KEY (id) REFERENCES gene_ontology(id));

CREATE TABLE mouse LIKE human;
CREATE TABLE fish LIKE human;
CREATE TABLE fly LIKE human;


LOAD DATA LOCAL INFILE 'goa_human.txt' INTO TABLE human;

SELECT id, Gene FROM human WHERE id = 'GO:0003700' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM human WHERE id = 'GO:0003700';

SELECT id, Gene FROM human WHERE id = 'GO:0007254' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM human WHERE id = 'GO:0007254';

SELECT id, Gene FROM human WHERE id = 'GO:0000380' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM human WHERE id = 'GO:0000380';

SELECT id, Gene FROM human WHERE id = 'GO:0001501' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM human WHERE id = 'GO:0001501';


LOAD DATA LOCAL INFILE 'goa_mouse.txt' INTO TABLE mouse;

SELECT id, Gene FROM mouse WHERE id = 'GO:0003700' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM mouse WHERE id = 'GO:0003700';

SELECT id, Gene FROM mouse WHERE id = 'GO:0007254' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM mouse WHERE id = 'GO:0007254';

SELECT id, Gene FROM mouse WHERE id = 'GO:0000380' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM mouse WHERE id = 'GO:0000380';

SELECT id, Gene FROM mouse WHERE id = 'GO:0001501' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM mouse WHERE id = 'GO:0001501';


LOAD DATA LOCAL INFILE 'goa_fish.txt' INTO TABLE fish;

SELECT id, Gene FROM fish WHERE id = 'GO:0003700' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fish WHERE id = 'GO:0003700';

SELECT id, Gene FROM fish WHERE id = 'GO:0007254' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fish WHERE id = 'GO:0007254';

SELECT id, Gene FROM fish WHERE id = 'GO:0000380' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fish WHERE id = 'GO:0000380';

SELECT id, Gene FROM fish WHERE id = 'GO:0001501' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fish WHERE id = 'GO:0001501';


LOAD DATA LOCAL INFILE 'goa_fly.txt' INTO TABLE fly;

SELECT id, Gene FROM fly WHERE id = 'GO:0003700' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fly WHERE id = 'GO:0003700';

SELECT id, Gene FROM fly WHERE id = 'GO:0007254' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fly WHERE id = 'GO:0007254';

SELECT id, Gene FROM fly WHERE id = 'GO:0000380' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fly WHERE id = 'GO:0000380';

SELECT id, Gene FROM fly WHERE id = 'GO:0001501' LIMIT 5;
SELECT COUNT(DISTINCT Gene) FROM fly WHERE id = 'GO:0001501';









zcat goa_human.gaf.gz | grep -v "\!" | grep "GO:0003700" | gawk 'BEGIN{FS="\t"}{print $3}' | sort | uniq | head -5
zcat goa_human.gaf.gz | grep -v "\!" | grep "GO:0003700" | gawk 'BEGIN{FS="\t"}{print $3}' | sort | uniq | wc -l

grep -v "\!" gene_association.goa_human_16sep2015 | gawk 'BEGIN{FS="\t"; OFS="\t"}{print $3, $5}' | sort | uniq > gene_association.txt

CREATE TABLE human ()
name varchar(200) NOT NULL,
id varchar(50) NOT NULL,
PRIMARY KEY (name,id),
FOREIGN KEY(id) REFERENCES gene_ontology(id));


SELECT human.id,function FROM human JOIN gene_ontology ON human.id=gene_ontology.id WHERE gene_ontology.function
LIKE "transcription factor activity" LIMIT 5;

SELECT Gene,id FROM human WHERE Gene LIKE "lmna" LIMIT 10;

SELECT Gene,human.id,function FROM human JOIN gene_ontology ON human.id=gene_ontology.id WHERE Gene
LIKE "lmna";
