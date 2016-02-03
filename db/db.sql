--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: auth_assignment; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE auth_assignment (
    item_name character varying(64) NOT NULL,
    user_id character varying(64) NOT NULL,
    created_at integer
);


ALTER TABLE public.auth_assignment OWNER TO vleermuizen;

--
-- Name: auth_item; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE auth_item (
    name character varying(64) NOT NULL,
    type integer NOT NULL,
    description text,
    rule_name character varying(64),
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE public.auth_item OWNER TO vleermuizen;

--
-- Name: auth_item_child; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE auth_item_child (
    parent character varying(64) NOT NULL,
    child character varying(64) NOT NULL
);


ALTER TABLE public.auth_item_child OWNER TO vleermuizen;

--
-- Name: auth_rule; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE auth_rule (
    name character varying(64) NOT NULL,
    data text,
    created_at integer,
    updated_at integer
);


ALTER TABLE public.auth_rule OWNER TO vleermuizen;

--
-- Name: closets_closet_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE closets_closet_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.closets_closet_id_seq OWNER TO vleermuizen;

--
-- Name: boxes; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE boxes (
    id integer DEFAULT nextval('closets_closet_id_seq'::regclass) NOT NULL,
    project_id integer NOT NULL,
    boxtype_id integer,
    cluster_id integer,
    code character varying(45) NOT NULL,
    placement_date date,
    removal_date date,
    cord_lat character varying(50) NOT NULL,
    cord_lng character varying(50) NOT NULL,
    location character varying(45),
    province character varying(20) NOT NULL,
    placement_height text,
    direction character varying(2) NOT NULL,
    picture character varying(100),
    remarks text,
    date_created timestamp(6) with time zone NOT NULL,
    date_updated timestamp(6) with time zone,
    deleted boolean DEFAULT false NOT NULL
);


ALTER TABLE public.boxes OWNER TO vleermuizen;

--
-- Name: boxtype_entrances; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE boxtype_entrances (
    id integer NOT NULL,
    boxtype_id integer NOT NULL,
    entrance_index numeric NOT NULL,
    other character varying
);


ALTER TABLE public.boxtype_entrances OWNER TO vleermuizen;

--
-- Name: boxtype_entrances_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE boxtype_entrances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.boxtype_entrances_id_seq OWNER TO vleermuizen;

--
-- Name: boxtype_entrances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE boxtype_entrances_id_seq OWNED BY boxtype_entrances.id;


--
-- Name: boxtypes_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE boxtypes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.boxtypes_id_seq OWNER TO vleermuizen;

--
-- Name: boxtypes; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE boxtypes (
    id integer DEFAULT nextval('boxtypes_id_seq'::regclass) NOT NULL,
    manufacturer_id integer,
    model character varying NOT NULL,
    shape smallint,
    shape_other text,
    chamber_count smallint,
    material smallint,
    material_other text,
    dropping_board smallint,
    dropping_board_other character varying,
    picture character varying,
    buildingplan character varying,
    height numeric,
    depth numeric,
    width numeric,
    entrance_width numeric,
    entrance_height numeric,
    minimal_crevice_width numeric,
    maximal_crevice_width numeric,
    date_created timestamp(6) with time zone NOT NULL,
    date_updated timestamp(6) with time zone,
    deleted boolean DEFAULT false NOT NULL
);


ALTER TABLE public.boxtypes OWNER TO vleermuizen;

--
-- Name: observation_methods; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE observation_methods (
    id integer NOT NULL,
    observation_id integer NOT NULL,
    method_index smallint NOT NULL,
    other text
);


ALTER TABLE public.observation_methods OWNER TO vleermuizen;

--
-- Name: observations; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE observations (
    id integer NOT NULL,
    visit_id integer NOT NULL,
    taxon_id integer,
    species_id integer,
    observation_type integer,
    age integer,
    sight_quantity integer,
    manure_collected boolean,
    manure_collection character varying(2044),
    manure_size integer,
    manure_quantity integer,
    catch_weight text,
    catch_sex integer,
    catch_forearm_right numeric,
    catch_forearm_left numeric,
    catch_sexual_status integer,
    catch_parasite_id integer,
    catch_parasite_collected boolean,
    catch_parasite_collection character varying(2044),
    picture character varying(2044),
    remarks text,
    catch_ring_code character varying(2044),
    catch_transponder_code character varying(2044),
    catch_radio_transmitter_code character varying(2044),
    catch_dna character varying(2044),
    dead boolean,
    deleted boolean DEFAULT false NOT NULL,
    date_created timestamp with time zone NOT NULL,
    date_updated timestamp with time zone,
    validated_by_id integer,
    validated_date timestamp with time zone,
    box_id integer NOT NULL,
    number integer DEFAULT 0 NOT NULL,
    ndff_id integer,
    ndff_failed timestamp with time zone
);


ALTER TABLE public.observations OWNER TO vleermuizen;

--
-- Name: observations_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE observations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.observations_id_seq OWNER TO vleermuizen;

--
-- Name: observations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE observations_id_seq OWNED BY observations.id;


--
-- Name: projects_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE projects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.projects_id_seq OWNER TO vleermuizen;

--
-- Name: projects; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE projects (
    id integer DEFAULT nextval('projects_id_seq'::regclass) NOT NULL,
    owner_id integer NOT NULL,
    main_observer_id integer NOT NULL,
    name character varying(45) NOT NULL,
    blur numeric(1,0) NOT NULL,
    remarks text,
    date_created timestamp(6) with time zone NOT NULL,
    date_updated timestamp(6) with time zone,
    deleted boolean DEFAULT false,
    embargo date
);


ALTER TABLE public.projects OWNER TO vleermuizen;

--
-- Name: species_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE species_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.species_id_seq OWNER TO vleermuizen;

--
-- Name: species; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE species (
    id integer DEFAULT nextval('species_id_seq'::regclass) NOT NULL,
    taxon integer DEFAULT 1 NOT NULL,
    genus character varying(45) NOT NULL,
    speceus character varying(45) NOT NULL,
    dutch character varying(45) NOT NULL,
    deleted boolean DEFAULT false NOT NULL,
    date_updated timestamp with time zone,
    url character varying(2044),
    date_created timestamp with time zone NOT NULL,
    ndff_species_url character varying(2044)
);


ALTER TABLE public.species OWNER TO vleermuizen;

--
-- Name: users; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    username character varying NOT NULL,
    auth_key character varying NOT NULL,
    fullname character varying NOT NULL
);


ALTER TABLE public.users OWNER TO vleermuizen;

--
-- Name: visit_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE visit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.visit_id_seq OWNER TO vleermuizen;

--
-- Name: visits; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE visits (
    id integer DEFAULT nextval('visit_id_seq'::regclass) NOT NULL,
    project_id integer NOT NULL,
    observer_id integer NOT NULL,
    date date NOT NULL,
    remarks text,
    deleted boolean DEFAULT false NOT NULL,
    date_created timestamp with time zone NOT NULL,
    date_updated timestamp with time zone,
    box_open integer DEFAULT 0 NOT NULL,
    cleaned integer DEFAULT 0 NOT NULL,
    count_completeness integer,
    blur integer,
    checked_all integer DEFAULT 0 NOT NULL,
    embargo date,
    observation_counter integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.visits OWNER TO vleermuizen;

--
-- Name: occurences; Type: VIEW; Schema: public; Owner: vleermuizen
--

CREATE VIEW occurences AS
 SELECT 'Thijs levert aan' AS rights,
    concat(owner.fullname,
        CASE
            WHEN (projects.owner_id <> projects.main_observer_id) THEN concat(', ', main_observer.fullname)
            ELSE NULL::text
        END,
        CASE
            WHEN ((visits.observer_id <> projects.owner_id) AND (visits.observer_id <> projects.main_observer_id)) THEN concat(', ', observer.fullname)
            ELSE NULL::text
        END) AS rightsholder,
    concat('http://app.vleermuizen.beta.swigledev.nl/observations/detail/', observations.id) AS "references",
    'vleermuiskasten.nl, Dutch Mammal Society, link THIJS' AS "institutionID",
    'Thijs levert aan' AS "informationWithheld",
        CASE
            WHEN (visits.blur <> 0) THEN (visits.blur)::numeric
            WHEN (projects.blur <> (0)::numeric) THEN projects.blur
            ELSE NULL::numeric
        END AS "dataGeneralizations",
    observations.remarks AS "occurrenceRemarks",
    observer.fullname AS "recordedBy",
    COALESCE(observations.catch_ring_code, observations.catch_transponder_code, observations.catch_radio_transmitter_code) AS "individualID",
    observations.sight_quantity AS "individualCount",
    observations.catch_sex AS sex,
    observations.age AS "lifeStage",
    observations.catch_sexual_status AS "reproductiveCondition",
        CASE
            WHEN (observations.picture IS NOT NULL) THEN concat('http://app.vleermuizen.beta.swigledev.nl/uploads/observations/', observations.picture)
            ELSE NULL::text
        END AS "associatedMedia",
    concat(boxes.code, ' - ', visits.id, ' - ', observations.number) AS "eventID",
    'Bat Box Count' AS "samplingProtocol",
    visits.date AS "eventDate",
    concat(projects.name, ' - ', projects.remarks) AS "eventRemarks",
    concat(projects.name, ' - ', boxes.code) AS "locationID",
    'NL' AS "countryCode",
    boxes.province AS provincie,
    boxes.cord_lng AS "decimalLongitude",
    boxes.cord_lat AS "decimalLatitude",
    'WGS84' AS "GeodaticDatum",
        CASE
            WHEN (observations.validated_by_id IS NOT NULL) THEN 1
            ELSE 0
        END AS "identificationVerificationStatus",
    'Thijs zoekt links uit' AS "taxonID",
    concat(species.genus, ' ', species.speceus) AS "scientificName",
    species.genus,
    'uit soorten, Thijs splitst' AS "specificEpithet",
    'soorten.beschrijving? Thijs?' AS "nameAuthorship"
   FROM (((((((observations
     LEFT JOIN visits ON ((observations.visit_id = visits.id)))
     LEFT JOIN boxes ON ((observations.box_id = boxes.id)))
     LEFT JOIN species ON ((observations.species_id = species.id)))
     LEFT JOIN projects ON ((visits.project_id = projects.id)))
     LEFT JOIN users owner ON ((projects.owner_id = owner.id)))
     LEFT JOIN users main_observer ON ((projects.main_observer_id = main_observer.id)))
     LEFT JOIN users observer ON ((visits.observer_id = observer.id)))
  WHERE (((visits.embargo IS NULL) OR (visits.embargo < now())) AND ((projects.embargo IS NULL) OR (projects.embargo < now())))
  ORDER BY visits.date DESC;


ALTER TABLE public.occurences OWNER TO vleermuizen;

--
-- Name: project_clusters; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE project_clusters (
    id integer NOT NULL,
    project_id integer NOT NULL,
    cluster character varying NOT NULL
);


ALTER TABLE public.project_clusters OWNER TO vleermuizen;

--
-- Name: project_cluster_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE project_cluster_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.project_cluster_id_seq OWNER TO vleermuizen;

--
-- Name: project_cluster_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE project_cluster_id_seq OWNED BY project_clusters.id;


--
-- Name: project_counters; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE project_counters (
    id integer NOT NULL,
    project_id integer NOT NULL,
    user_id integer NOT NULL
);


ALTER TABLE public.project_counters OWNER TO vleermuizen;

--
-- Name: project_counters_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE project_counters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.project_counters_id_seq OWNER TO vleermuizen;

--
-- Name: project_counters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE project_counters_id_seq OWNED BY project_counters.id;


--
-- Name: session; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE session (
    id character(64) NOT NULL,
    expire integer,
    data bytea
);


ALTER TABLE public.session OWNER TO vleermuizen;

--
-- Name: visit_boxes; Type: TABLE; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE TABLE visit_boxes (
    id integer NOT NULL,
    visit_id integer,
    box_id integer
);


ALTER TABLE public.visit_boxes OWNER TO vleermuizen;

--
-- Name: visit_closets_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE visit_closets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.visit_closets_id_seq OWNER TO vleermuizen;

--
-- Name: visit_closets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE visit_closets_id_seq OWNED BY visit_boxes.id;


--
-- Name: visit_method_id_seq; Type: SEQUENCE; Schema: public; Owner: vleermuizen
--

CREATE SEQUENCE visit_method_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.visit_method_id_seq OWNER TO vleermuizen;

--
-- Name: visit_method_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: vleermuizen
--

ALTER SEQUENCE visit_method_id_seq OWNED BY observation_methods.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY boxtype_entrances ALTER COLUMN id SET DEFAULT nextval('boxtype_entrances_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY observation_methods ALTER COLUMN id SET DEFAULT nextval('visit_method_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY observations ALTER COLUMN id SET DEFAULT nextval('observations_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY project_clusters ALTER COLUMN id SET DEFAULT nextval('project_cluster_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY project_counters ALTER COLUMN id SET DEFAULT nextval('project_counters_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY visit_boxes ALTER COLUMN id SET DEFAULT nextval('visit_closets_id_seq'::regclass);


--
-- Name: auth_assignment_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_pkey PRIMARY KEY (item_name, user_id);


--
-- Name: auth_item_child_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_pkey PRIMARY KEY (parent, child);


--
-- Name: auth_item_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_pkey PRIMARY KEY (name);


--
-- Name: auth_rule_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY auth_rule
    ADD CONSTRAINT auth_rule_pkey PRIMARY KEY (name);


--
-- Name: boxtype_entrances_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY boxtype_entrances
    ADD CONSTRAINT boxtype_entrances_pkey PRIMARY KEY (id);


--
-- Name: boxtypes_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY boxtypes
    ADD CONSTRAINT boxtypes_pkey PRIMARY KEY (id);


--
-- Name: closets_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY boxes
    ADD CONSTRAINT closets_pkey PRIMARY KEY (id);


--
-- Name: observations_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY visits
    ADD CONSTRAINT observations_pkey PRIMARY KEY (id);


--
-- Name: observations_pkey1; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY observations
    ADD CONSTRAINT observations_pkey1 PRIMARY KEY (id);


--
-- Name: project_cluster_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY project_clusters
    ADD CONSTRAINT project_cluster_pkey PRIMARY KEY (id);


--
-- Name: projects_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY projects
    ADD CONSTRAINT projects_pkey PRIMARY KEY (id);


--
-- Name: session_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY session
    ADD CONSTRAINT session_pkey PRIMARY KEY (id);


--
-- Name: species_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY species
    ADD CONSTRAINT species_pkey PRIMARY KEY (id);


--
-- Name: un_name; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY projects
    ADD CONSTRAINT un_name UNIQUE (name);


--
-- Name: un_projectid_cluster; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY project_clusters
    ADD CONSTRAINT un_projectid_cluster UNIQUE (project_id, cluster);


--
-- Name: un_visit_closet; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY visit_boxes
    ADD CONSTRAINT un_visit_closet UNIQUE (box_id, visit_id);


--
-- Name: unique_id; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY project_counters
    ADD CONSTRAINT unique_id UNIQUE (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: visit_closets_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY visit_boxes
    ADD CONSTRAINT visit_closets_pkey PRIMARY KEY (id);


--
-- Name: visit_method_pkey; Type: CONSTRAINT; Schema: public; Owner: vleermuizen; Tablespace: 
--

ALTER TABLE ONLY observation_methods
    ADD CONSTRAINT visit_method_pkey PRIMARY KEY (id);


--
-- Name: idx-auth_item-type; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX "idx-auth_item-type" ON auth_item USING btree (type);


--
-- Name: index_ndff_id; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX index_ndff_id ON observations USING btree (ndff_id);


--
-- Name: index_observation_counter; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX index_observation_counter ON visits USING btree (observation_counter);


--
-- Name: index_species_id; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX index_species_id ON observations USING btree (species_id);


--
-- Name: index_taxon_id; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX index_taxon_id ON observations USING btree (taxon_id);


--
-- Name: index_visit_id; Type: INDEX; Schema: public; Owner: vleermuizen; Tablespace: 
--

CREATE INDEX index_visit_id ON observations USING btree (visit_id);


--
-- Name: auth_assignment_item_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY auth_assignment
    ADD CONSTRAINT auth_assignment_item_name_fkey FOREIGN KEY (item_name) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_child_fkey; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_child_fkey FOREIGN KEY (child) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_child_parent_fkey; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY auth_item_child
    ADD CONSTRAINT auth_item_child_parent_fkey FOREIGN KEY (parent) REFERENCES auth_item(name) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: auth_item_rule_name_fkey; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY auth_item
    ADD CONSTRAINT auth_item_rule_name_fkey FOREIGN KEY (rule_name) REFERENCES auth_rule(name) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: boxtypes_manufacturer_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY boxtypes
    ADD CONSTRAINT boxtypes_manufacturer_id_fkey FOREIGN KEY (manufacturer_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE SET NULL;


--
-- Name: fk_closet_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY visit_boxes
    ADD CONSTRAINT fk_closet_id FOREIGN KEY (box_id) REFERENCES boxes(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_closets_boxtype_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY boxes
    ADD CONSTRAINT fk_closets_boxtype_id FOREIGN KEY (boxtype_id) REFERENCES boxtypes(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: fk_closets_cluster_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY boxes
    ADD CONSTRAINT fk_closets_cluster_id FOREIGN KEY (cluster_id) REFERENCES project_clusters(id) ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: fk_main_observer_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY projects
    ADD CONSTRAINT fk_main_observer_id FOREIGN KEY (owner_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: fk_observeration_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY visits
    ADD CONSTRAINT fk_observeration_id FOREIGN KEY (observer_id) REFERENCES users(id) ON DELETE CASCADE;


--
-- Name: fk_owner_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY projects
    ADD CONSTRAINT fk_owner_id FOREIGN KEY (main_observer_id) REFERENCES users(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: fk_pc_project_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY project_counters
    ADD CONSTRAINT fk_pc_project_id FOREIGN KEY (project_id) REFERENCES projects(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_pc_user_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY project_counters
    ADD CONSTRAINT fk_pc_user_id FOREIGN KEY (user_id) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: fk_project_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY boxes
    ADD CONSTRAINT fk_project_id FOREIGN KEY (project_id) REFERENCES projects(id) MATCH FULL ON UPDATE SET NULL ON DELETE SET NULL;


--
-- Name: fk_visit_id; Type: FK CONSTRAINT; Schema: public; Owner: vleermuizen
--

ALTER TABLE ONLY visit_boxes
    ADD CONSTRAINT fk_visit_id FOREIGN KEY (visit_id) REFERENCES visits(id) MATCH FULL ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

